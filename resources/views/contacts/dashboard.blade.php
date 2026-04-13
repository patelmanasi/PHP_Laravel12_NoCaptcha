<!DOCTYPE html>
<html>
<head>
    <title>Contact Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-6xl mx-auto mt-10">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-3xl font-bold">📩 Contact Dashboard</h1>

        <a href="/contact/trash"
           class="bg-red-500 text-white px-4 py-2 rounded">
            🗑 Trash
        </a>
    </div>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- SEARCH -->
    <form method="GET" class="mb-4 flex">
        <input type="text" name="search"
               placeholder="Search name or email..."
               class="border p-2 w-full rounded-l">

        <button class="bg-blue-500 text-white px-4 px-4 rounded-r">
            Search
        </button>
    </form>

    <!-- TABLE -->
    <div class="bg-white shadow rounded p-4">

        <table class="w-full">
            <tr class="bg-gray-200">
                <th class="p-2">ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Action</th>
            </tr>

            @foreach($contacts as $contact)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-2">{{ $contact->id }}</td>
                <td>{{ $contact->name }}</td>
                <td>{{ $contact->email }}</td>
                <td>{{ $contact->message }}</td>
                <td>
                    <a href="/contact/delete/{{ $contact->id }}"
                       onclick="return confirm('Move to trash?')"
                       class="text-red-600">
                        Delete
                    </a>
                </td>
            </tr>
            @endforeach

        </table>

    </div>
</div>

</body>
</html>