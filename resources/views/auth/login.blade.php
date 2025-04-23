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
        <h2 class="text-3xl font-bold text-center text-red-900 mb-6">Login</h2>
        <form class="space-y-5" method="POST" action="{{ route('login') }}">
            @csrf
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700" for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username"
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-600 transition" />
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700" for="password">Password</label>
                <input type="password" id="password" placeholder="••••••••" name="password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-600 transition" />
            </div>

            <button type="submit"
                class="w-full bg-red-700 hover:bg-red-800 text-white font-semibold py-2 rounded-xl transition duration-300">
                Sign In
            </button>
        </form>

    </div>
</body>

</html>
