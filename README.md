# PHP_Laravel12_NoCaptcha


## Introduction

PHP_Laravel12_NoCaptcha is a beginner-friendly Laravel 12 project that demonstrates how to securely integrate Google reCAPTCHA into a Laravel application to protect web forms from bots and automated submissions while storing validated user data in a MySQL database.
This project emphasizes real-world form security, a clean MVC architecture, and modern Laravel validation practices, making it ideal for learning purposes, academic submission, and developer portfolio showcase.

---

## Project Overview

This application implements a secure contact form workflow that includes:

- Integration of Google reCAPTCHA v2 to prevent bot submissions

- Server-side validation using modern Laravel request validation

- Database storage of user messages through Eloquent ORM

- Clean MVC structure following Laravel 12 best practices

- Responsive modern UI design built with Tailwind CSS

---

## Features:

- Laravel 12 setup

- Google reCAPTCHA v2 ("I'm not a robot") integration

- Invisible reCAPTCHA integration

- Form submission validation

- Save form submissions (name, email, message) into the database

- Proper folder structure with clean, maintainable code

---

## Step 1: Create Laravel 12 Project

Open terminal and run:

```bash
composer create-project laravel/laravel PHP_Laravel12_NoCaptcha "12.*"
cd PHP_Laravel12_NoCaptcha
```

---

## Step 2: Configure Database

Open .env and configure your database:

```.env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_nocaptcha
DB_USERNAME=root
DB_PASSWORD=
```

---

## Step 3: Install CAPTCHA Package

```bash
composer require anhskohbo/no-captcha
```

This package provides helpers to display and validate Google reCAPTCHA.

---

## Step 4: Create Google reCAPTCHA Keys

### 1) Go to **Google reCAPTCHA Admin Console**:

-  [Open Google reCAPTCHA](https://www.google.com/recaptcha/admin/create)

### 2) Register a new site:

- **Label:** Laravel12 NoCaptcha

- **Type:** reCAPTCHA v2 (Checkbox) and Invisible reCAPTCHA

- **Domains:** localhost (for testing) and 127.0.0.1 both add

### 3) Copy the **Site Key** and **Secret Key**:

Add your Site Key and Secret Key to .env:

```
NOCAPTCHA_SITEKEY=your_site_key_here
NOCAPTCHA_SECRET=your_secret_key_here
```

---

## Step 5: Configure CAPTCHA in Laravel

Add the keys in config/services.php:

```
'captcha' => [
    'sitekey' => env('NOCAPTCHA_SITEKEY'),
    'secret' => env('NOCAPTCHA_SECRET'),
],
```

---

## Step 6: Create Contacts Table Migration

```bash
php artisan make:migration create_contacts_table --create=contacts
```

File: database/migrations/xxxx_xx_xx_create_contacts_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
```

Run the migration:

```bash
php artisan migrate
```

---

## Step 7: Create Contact Model

```bash
php artisan make:model Contact
```

File: app/Models/Contact.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'message'];
}
```

---

## Step 8: Create Controller

```bash
php artisan make:controller ContactController
```

File: app/Http/Controllers/ContactController.php

```bash
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function showForm()
    {
        return view('contact');
    }

    public function submitForm(Request $request)
    {
        //  Modern Laravel validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
            'g-recaptcha-response' => 'required|captcha'
        ]);

        //  Save submission to database
        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
        ]);

        //  Redirect with success message
        return back()->with('success', 'Form submitted and saved successfully!');
    }
}
```

---

## Step 9: Create Routes

File: routes/web.php

```php
use App\Http\Controllers\ContactController;

Route::get('/contact', [ContactController::class, 'showForm'])->name('contact.form');
Route::post('/contact', [ContactController::class, 'submitForm'])->name('contact.submit');
```

---

## Step 10: Create Blade View

File: resources/views/contact.blade.php

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body class="bg-gradient-to-br from-indigo-50 via-white to-purple-50 min-h-screen flex items-center justify-center">

    <!-- Main Card -->
    <div class="w-full max-w-xl bg-white shadow-2xl rounded-2xl p-8">

        <!-- Heading -->
        <h1 class="text-3xl font-bold text-gray-800 text-center mb-2">
            Contact Us
        </h1>
        <p class="text-gray-500 text-center mb-6">
            We'd love to hear from you. Fill out the form below.
        </p>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Global Errors -->
        @if($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4">
                <ul class="list-disc ml-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('contact.submit') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    placeholder="Enter your name"
                >
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    placeholder="Enter your email"
                >
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Message -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                <textarea
                    name="message"
                    rows="4"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    placeholder="Write your message..."
                >{{ old('message') }}</textarea>
                @error('message')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- CAPTCHA -->
            <div class="flex justify-center">
                {!! NoCaptcha::display() !!}
            </div>

            @error('g-recaptcha-response')
                <p class="text-red-500 text-sm text-center">{{ $message }}</p>
            @enderror

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg transition duration-200 shadow-md"
            >
                Send Message
            </button>
        </form>
    </div>

    <!-- Render CAPTCHA JS -->
    {!! NoCaptcha::renderJs() !!}
</body>
</html>
```

---

## Step 11: Run the Project

```bash
php artisan serve
```

Visit: 

```bash
http://localhost:8000/contact
```

Submit the form and it will validate the CAPTCHA and save the data to the database.

---

## Output

### Contact Us Page With reCAPTCHA

<img width="1919" height="1030" alt="Screenshot 2026-02-05 111813" src="https://github.com/user-attachments/assets/3907ba4e-b378-4fae-b9ad-83014d859095" />

<img width="1919" height="1031" alt="Screenshot 2026-02-05 111827" src="https://github.com/user-attachments/assets/5c245993-c2ce-46ea-a171-3a3ebd2af8f1" />

### Validation Error Without reCAPTCHA

<img width="1917" height="1029" alt="Screenshot 2026-02-05 111735" src="https://github.com/user-attachments/assets/5def0033-675f-40f4-999a-1932ba64b888" />

## Project Folder Structure

```
PHP_Laravel12_NoCaptcha/
├─ app/
│  ├─ Http/
│  │  └─ Controllers/
│  │     └─ ContactController.php
│  ├─ Models/
│  │  └─ Contact.php
├─ config/
│  └─ services.php
├─ database/
│  └─ migrations/
│     └─ create_contacts_table.php
├─ resources/
│  └─ views/
│     └─ contact.blade.php
├─ routes/
│  └─ web.php
├─ .env
├─ composer.json
├─ artisan
```

---

Your PHP_Laravel12_NoCaptcha Project is now ready!
