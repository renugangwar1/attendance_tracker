<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Tailwind CDN (only if Tailwind isn't already compiled via Vite) -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Login to Your Account</h2>

        @if($errors->any())
            <div class="mb-4 text-red-600 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input type="email" name="email" id="email" placeholder="you@gmail.com"
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500" required>
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" placeholder="••••••••"
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500" required>
            </div>

            <button type="submit"
                    class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-md transition duration-200">
                Login
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            Don't have an account?
            <a href="{{ route('register.page') }}" class="text-red-500 hover:underline font-medium">Register here</a>
        </p>
    </div>

</body>
</html>
