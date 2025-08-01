<div class="row mb-4">
    <div class="col-md-6">
        <form method="GET" class="d-flex flex-column flex-sm-row gap-2 w-100">
            <input type="text" name="search" class="form-control flex-grow-1" 
                   placeholder="<?= $placeholder ?? 'Search for a person...' ?>" 
                   value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i> Search
            </button>
            <?php if (!empty($_GET['search'])): ?>
                <a href="<?= basename($_SERVER['PHP_SELF']) ?>" class="btn btn-outline-secondary">
                    Clear
                </a>
            <?php endif; ?>
        </form>
    </div>
</div>

<?php
// Example: Filter users based on search input
if (isset($_GET['search']) && $_GET['search'] !== '') {
    $search = trim($_GET['search']);
    // Replace this with your actual user fetching/filtering logic
    // Example: $users = getUsersFilteredByName($search);
    // foreach ($users as $user) { echo htmlspecialchars($user['name']); }
}
?>