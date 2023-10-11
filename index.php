<?php
$jsonFile = 'books.json';

// Read JSON file
if (file_exists($jsonFile)) {
    $booksData = file_get_contents($jsonFile);
    $books = json_decode($booksData, true);
} else {
    die('Error: books.json file not found.');
}

// Search Function
function searchBooks($query, $books) {
    $results = [];
    foreach ($books as $book) {
        if (stripos($book['title'], $query) !== false || stripos($book['author'], $query) !== false) {
            $results[] = $book;
        }
    }
    return $results;
}

// Save Function
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title']) && isset($_POST['author'])) {
    $newBook = [
        'title' => $_POST['title'],
        'author' => $_POST['author'],
        'available' => true,
        'pages' => (int)$_POST['pages'],
        'isbn' => (int)$_POST['isbn']
    ];

    $books[] = $newBook;
    $newJsonData = json_encode($books, JSON_PRETTY_PRINT);
    file_put_contents($jsonFile, $newJsonData);
}

// Delete Function
if (isset($_GET['delete'])) {
    $index = $_GET['delete'];
    if (isset($books[$index])) {
        unset($books[$index]);
        $newJsonData = json_encode(array_values($books), JSON_PRETTY_PRINT);
        file_put_contents($jsonFile, $newJsonData);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Library</title>
</head>
<body>
    <h1>Book Library</h1>

    <!-- Search Form -->
    <form action="" method="get">
        <input type="text" name="query" placeholder="Search for books">
        <input type="submit" value="Search">
    </form>

    <!-- Display Books in Table -->
    <table border="1">
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Available</th>
            <th>Pages</th>
            <th>ISBN</th>
            <th>Action</th>
        </tr>
        <?php
        foreach ($books as $index => $book) {
            echo "<tr>
                    <td>{$book['title']}</td>
                    <td>{$book['author']}</td>
                    <td>" . ($book['available'] ? 'Yes' : 'No') . "</td>
                    <td>{$book['pages']}</td>
                    <td>{$book['isbn']}</td>
                    <td><a href=\"?delete={$index}\">Delete</a></td>
                </tr>";
        }
        ?>
    </table>

    <!-- Add New Book Form -->
    <h2>Add New Book</h2>
    <form action="" method="post">
        Title: <input type="text" name="title" required><br>
        Author: <input type="text" name="author" required><br>
        Pages: <input type="number" name="pages" required><br>
        ISBN: <input type="number" name="isbn" required><br>
        <input type="submit" value="Add Book">
    </form>
</body>
</html>
