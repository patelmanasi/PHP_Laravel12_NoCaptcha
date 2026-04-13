<!DOCTYPE html>
<html>
<head>
    <title>Contact Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body class="bg-gray-100 flex justify-center items-center min-h-screen">

<div class="bg-white p-6 rounded shadow w-96">

    <h2 class="text-2xl font-bold mb-4">Contact Us</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2 mb-3">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('contact.submit') }}">
        @csrf

        <input type="text" name="name" placeholder="Name"
               class="w-full border p-2 mb-2">

        <input type="email" name="email" placeholder="Email"
               class="w-full border p-2 mb-2">

        <textarea name="message" placeholder="Message"
                  class="w-full border p-2 mb-2"></textarea>

        <!-- CAPTCHA -->
        <div class="mb-2">
            {!! NoCaptcha::display() !!}
        </div>

        @error('g-recaptcha-response')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror

        <button class="bg-blue-500 text-white w-full p-2">
            Send
        </button>

    </form>

    {!! NoCaptcha::renderJs() !!}
</div>

</body>
</html>