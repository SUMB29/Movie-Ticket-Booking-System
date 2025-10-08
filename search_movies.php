<?php
// Include your database connection
require_once 'connect.php';  
try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Search
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

$sql = "SELECT m.*, c.catname, 
               t.name AS theatre_name, 
               t.location AS theatre_location,
               t.price AS theatre_price,
               t.days AS theatre_days
        FROM movies m
        LEFT JOIN categories c ON m.catid = c.catid
        LEFT JOIN theatre t ON m.movieid = t.movieid
        WHERE 1";

$params = [];

if ($search !== '') {
    $sql .= " AND (m.title LIKE :search 
                OR c.catname LIKE :search 
                OR t.name LIKE :search 
                OR t.location LIKE :search)";
    $params[':search'] = "%$search%";
}

$sql .= " ORDER BY m.movieid DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$movies) {
    echo '<p class="text-white text-center col-span-3">No movies found.</p>';
    exit;
}

// Group theatres under each movie
$groupedMovies = [];
foreach ($movies as $row) {
    $movieId = $row['movieid'];
    if (!isset($groupedMovies[$movieId])) {
        $groupedMovies[$movieId] = [
            'details' => $row,
            'theatres' => []
        ];
    }
    if ($row['theatre_name']) {
        $groupedMovies[$movieId]['theatres'][] = [
            'name' => $row['theatre_name'],
            'location' => $row['theatre_location'],
            'price' => $row['theatre_price'],
            'days' => $row['theatre_days']
        ];
    }
}

foreach ($groupedMovies as $movie): 
    $row = $movie['details']; ?>
    <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg">
        <img src="admin/uploads/<?= htmlspecialchars($row['image']) ?>" 
             alt="<?= htmlspecialchars($row['title']) ?>" 
             class="w-full h-64 object-cover">
        <div class="p-4">
            <h3 class="text-xl font-bold text-white"><?= htmlspecialchars($row['title']) ?></h3>
            <p class="text-gray-300 mt-2">Category: <?= htmlspecialchars($row['catname']) ?></p>
            
            <?php if (!empty($movie['theatres'])): ?>
                <p class="text-gray-300 mt-2 font-semibold">Available in Theatres:</p>
                <ul class="list-disc list-inside text-gray-400">
                        <?php foreach ($movie['theatres'] as $t): ?>
                        <li>
                            <?= htmlspecialchars($t['name']) ?> (<?= htmlspecialchars($t['location']) ?>)  
                            - <span class="text-yellow-400 font-medium">₹<?= htmlspecialchars($t['price']) ?></span>  
                            - <span class="text-green-400"><?= htmlspecialchars($t['days']) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-gray-500 mt-2">No theatre information available.</p>
            <?php endif; ?>
            
            <button type="button" class="btn bg-yellow-400 font-medium hover:bg-yellow-300 p-2 rounded-2xl mt-3">
                <a href="admin/uploads/<?= htmlspecialchars($row['trailer']) ?>" target="_blank">Watch Trailer</a>
            </button>
            <a href="book.php?movie=<?= $row['movieid'] ?>" 
               class="mt-4 inline-block bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                Book Now
            </a>
        </div>
    </div>
<?php endforeach; ?>
