<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-r from-red-900 via-red-700 to-red-500">
    <div class="w-full max-w-sm p-6 bg-white rounded-2xl shadow-2xl">

        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <img src="{{ asset('/assets/img/logo.png') }}" alt="Logo" class="h-16 w-auto">
        </div>

        <h2 class="text-3xl font-bold text-center text-red-900 mb-6">Login</h2>

        <!-- Display general error (e.g. auth.failed) -->
        @if ($errors->has('username'))
            <div class="mb-4 text-center text-red-600 text-sm">
                {{ $errors->first('username') }}
            </div>
        @endif

        <form class="space-y-5" method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Username -->
            <div>
                <label for="username" class="block mb-1 text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}"
                    placeholder="Enter your username"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-red-600 transition @error('username') border-red-600 @enderror"
                    required />
                @error('username')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block mb-1 text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" placeholder="••••••••"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-red-600 transition @error('password') border-red-600 @enderror"
                    required />
                @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-red-700 hover:bg-red-800 text-white font-semibold py-2 rounded-xl transition duration-300">
                Sign In
            </button>
        </form>
    </div>
</body>

</html>
