<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller {
    // GET /api/users
    public function index(Request $request) {
        $query = User::with(['profile','addresses']);

        if ($request->filled('name')) {
            $query->where('name', 'like', '%'.$request->query('name').'%');
        }
        if ($request->filled('cpf')) {
            $cpf = preg_replace('/\D/','',$request->query('cpf'));
            $query->where('cpf','like', "%{$cpf}%");
        }
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $from = $request->query('date_from') . ' 00:00:00';
            $to = $request->query('date_to') . ' 23:59:59';
            $query->whereBetween('created_at', [$from, $to]);
        } elseif ($request->filled('date_from')) {
            $from = $request->query('date_from') . ' 00:00:00';
            $query->where('created_at', '>=', $from);
        } elseif ($request->filled('date_to')) {
            $to = $request->query('date_to') . ' 23:59:59';
            $query->where('created_at', '<=', $to);
        }

        $perPage = $request->query('per_page', 10);
        $users = $query->orderBy('created_at','desc')->paginate($perPage);

        return UserResource::collection($users);
    }

    // POST /api/users
    public function store(StoreUserRequest $request) {
        DB::transaction(function() use ($request, &$user) {
            $data = $request->only(['name','email','cpf','profile_id']);
            $user = User::create($data);

            if ($request->filled('addresses')) {
                foreach ($request->addresses as $addr) {
                    // tentamos encontrar endereço existente por campos-chave (opcional)
                    $existing = Address::where('street', $addr['street'])
                        ->where('city', $addr['city'])
                        ->where('state', $addr['state'])
                        ->first();

                    if ($existing) {
                        $user->addresses()->attach($existing->id);
                    } else {
                        $new = Address::create($addr);
                        $user->addresses()->attach($new->id);
                    }
                }
            }
        });

        /** @var \App\Models\User $user */
        return new UserResource($user->load(['profile','addresses']));
    }

    // GET /api/users/{user}
    public function show(User $user) {
        return new UserResource($user->load(['profile','addresses']));
    }

    // PUT/PATCH /api/users/{user}
    public function update(UpdateUserRequest $request, User $user) {
        DB::transaction(function() use ($request, $user) {
            $user->update($request->only(['name','email','cpf','profile_id']));

            if ($request->has('addresses')) {
                // estratégia: sincroniza os endereços — cria novos se necessário
                $addressIds = [];
                foreach ($request->addresses as $addr) {
                    if (isset($addr['id'])) {
                        $addressIds[] = $addr['id'];
                    } else {
                        $new = Address::create($addr);
                        $addressIds[] = $new->id;
                    }
                }
                $user->addresses()->sync($addressIds);
            }
        });

        return new UserResource($user->load(['profile','addresses']));
    }

    // DELETE /api/users/{user}
    public function destroy(User $user) {
        $user->delete(); // cascade no pivot
        return response()->json(['message'=>'Usuário excluído'], 200);
    }
}