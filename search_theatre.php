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

$sql = "SELECT m.*,
               t.theatreid,
               t.name AS theatre_name, 
               t.location AS theatre_location,
               t.price AS theatre_price,
               t.days AS theatre_days
        FROM theatre t
        LEFT JOIN movies m ON m.movieid = t.movieid
        WHERE 1";

$params = [];

if ($search !== '') {
    $sql .= " AND (m.title LIKE :search 
                OR t.name LIKE :search 
                OR t.location LIKE :search)";
    $params[':search'] = "%$search%";
}

$sql .= " ORDER BY t.theatreid DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$results) {
    echo '<p class="text-white text-center col-span-3">No theatres found.</p>';
    exit;
}

// Group movies under each theatre
$groupedTheatres = [];
foreach ($results as $row) {
    $theatreId = $row['theatreid'];
    if (!isset($groupedTheatres[$theatreId])) {
        $groupedTheatres[$theatreId] = [
            'details' => [
                'name' => $row['theatre_name'],
                'location' => $row['theatre_location'],
                'price' => $row['theatre_price'],
                'days' => $row['theatre_days']
            ],
            'movies' => []
        ];
    }
    if ($row['title']) {
        $groupedTheatres[$theatreId]['movies'][] = [
            'title' => $row['title'],
            'image' => $row['image'],
            'description' => $row['description']
        ];
    }
}

foreach ($groupedTheatres as $theatre): 
    $t = $theatre['details']; ?>
    <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg p-4">
        <h3 class="text-xl font-bold text-white">
            <?= htmlspecialchars($t['name']) ?> (<?= htmlspecialchars($t['location']) ?>)
        </h3>
        <p class="text-yellow-400 mt-1 font-medium">₹<?= htmlspecialchars($t['price']) ?> | 
           <span class="text-green-400"><?= htmlspecialchars($t['days']) ?></span>
        </p>

        <?php if (!empty($theatre['movies'])): ?>
            <p class="text-gray-300 mt-3 font-semibold">Movies running here:</p>
            <div class="grid grid-cols-2 gap-4 mt-2">
                <?php foreach ($theatre['movies'] as $m): ?>
                    <div class="bg-gray-700 rounded-lg p-3 shadow">
                        <?php if (!empty($m['image'])): ?>
                            <img src="admin/uploads/<?= htmlspecialchars($m['image']) ?>" 
                                 alt="<?= htmlspecialchars($m['title']) ?>" 
                                 class="w-full h-40 object-cover rounded-md mb-2">
                        <?php endif; ?>
                        <h4 class="text-white font-bold"><?= htmlspecialchars($m['title']) ?></h4>
                        <p class="text-gray-400 text-sm"><?= htmlspecialchars($m['description']) ?></p>
                                    <button type="button" class="btn bg-yellow-400 font-medium hover:bg-yellow-300 p-2 rounded mt-2">
                <a href="admin/uploads/<?= htmlspecialchars($row['trailer']) ?>" target="_blank">Watch Trailer</a>
            </button>
            <a href="book.php?movie=<?= $row['movieid'] ?>" 
               class="mt-2 inline-block bg-red-600 hover:bg-red-700 text-white p-3 rounded">
                Book Now
            </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-gray-500 mt-2">No movies listed for this theatre.</p>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
