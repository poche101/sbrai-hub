<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login — Sbrai Solutions</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">

  <div class="w-full max-w-sm">
    <div class="text-center mb-8">
  <img src="/images/app.jpeg" alt="logo" class="mx-auto" style="width: 55px; border-radius:7px;">
  <br>
  <h1 class="text-xl font-bold text-gray-900 mt-2">Sbrai Admin Panel</h1>
  <p class="text-sm text-gray-400 mt-1">Sign in to manage the platform</p>
</div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
      @if($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 text-red-600 text-sm font-medium px-4 py-3 rounded-xl">
          {{ $errors->first() }}
        </div>
      @endif

      <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
        @csrf
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
          <input type="email" name="email" value="admin@sbrai.com" required
            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
          <input type="password" name="password" required
            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
        </div>
        <label class="flex items-center gap-2 text-sm text-gray-500">
          <input type="checkbox" name="remember" class="rounded border-gray-300 text-orange-500 focus:ring-orange-500">
          Remember me
        </label>
        <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold text-sm py-3.5 rounded-xl shadow-md hover:opacity-90 transition">
          Sign In to Admin Panel
        </button>
      </form>
    </div>

    <p class="text-center text-xs text-gray-400 mt-6">Sbrai Solutions Limited &copy; {{ date('Y') }}</p>
  </div>

</body>
</html>
