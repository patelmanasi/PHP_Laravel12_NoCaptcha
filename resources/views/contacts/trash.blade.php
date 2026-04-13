<!DOCTYPE html>
<html>
<head>
    <title>Trash Contacts</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-6xl mx-auto mt-10">

    <h1 class="text-3xl font-bold mb-5 text-red-600">🗑 Trash</h1>

    <a href="/contact/dashboard" class="text-blue-500">
        ← Back to Dashboard
    </a>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 mt-3 mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded p-4 mt-4">

        <table class="w-full">
            <tr class="bg-gray-200">
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>

            @foreach($contacts as $contact)
            <tr class="border-b">
                <td>{{ $contact->id }}</td>
                <td>{{ $contact->name }}</td>
                <td>{{ $contact->email }}</td>
                <td>
                    <a href="/contact/restore/{{ $contact->id }}"
                       class="text-green-600">
                        Restore
                    </a>
                    |
                    <a href="/contact/force-delete/{{ $contact->id }}"
                       class="text-red-600"
                       onclick="return confirm('Delete forever?')">
                        Delete Forever
                    </a>
                </td>
            </tr>
            @endforeach

        </table>

    </div>
</div>

</body>
</html>