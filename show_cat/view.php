<?php
include 'config/db.php';
include 'includes/header.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$upload_path = "uploads/"; 

if ($id > 0) {
    // ==========================================
    // ส่วนแสดงรายละเอียด (Detail Mode)
    // ==========================================
    $sql = "SELECT * FROM CatBreeds WHERE id = $id";
    $res = $conn->query($sql);
    $cat = $res->fetch_assoc();

    if ($cat) {
        $cat_id = $cat['id'];
        $main_image = !empty($cat['image_url']) ? $cat['image_url'] : 'no-image.jpg';

        $img_sql = "SELECT * FROM cat_images 
                    WHERE cat_id = $cat_id 
                    AND image_url != '$main_image' 
                    LIMIT 3";
        $images = $conn->query($img_sql);
        ?>
        <div class="container py-5">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="view.php" style="color: #5c5be5; text-decoration: none;">คลังสายพันธุ์แมว</a></li>
                    <li class="breadcrumb-item active"><?php echo htmlspecialchars($cat['name_th']); ?></li>
                </ol>
            </nav>

            <div class="row g-4">
                <div class="col-lg-6"> <div class="main-image mb-3 shadow rounded-4 overflow-hidden" style="height: 500px; background: #eee;">
                        <img src="<?php echo $upload_path . $main_image; ?>" 
                             class="w-100 h-100" 
                             style="object-fit: cover; transition: 0.3s;" 
                             id="largeImage"
                             onerror="this.src='uploads/no-image.jpg'">
                    </div>
                    
                    <div class="row g-2">
                        <?php while($img = $images->fetch_assoc()): ?>
                            <div class="col-3">
                                <div class="rounded-3 shadow-sm overflow-hidden border" style="height: 80px; cursor: pointer;">
                                    <img src="<?php echo $upload_path . $img['image_url']; ?>" 
                                         class="w-100 h-100" 
                                         style="object-fit: cover;" 
                                         onclick="document.getElementById('largeImage').src=this.src"
                                         onerror="this.style.display='none'">
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="mb-4">
                        <h1 class="fw-bold display-5" style="color: #333;"><?php echo htmlspecialchars($cat['name_th']); ?></h1>
                        <p class="text-muted fs-4 fst-italic"><?php echo htmlspecialchars($cat['name_en']); ?></p>
                    </div>

                    <div class="p-4 bg-white rounded-4 shadow-sm mb-4 text-start">
                        <h5 class="fw-bold border-bottom pb-2 mb-3"><i class="bi bi-info-circle-fill me-2 text-primary"></i>รายละเอียดสายพันธุ์</h5>
                        <p class="text-secondary" style="line-height: 1.8; text-align: justify;">
                            <?php echo nl2br(htmlspecialchars($cat['description'])); ?>
                        </p>
                    </div>

                    <?php if(!empty($cat['characteristics'])): ?>
                    <div class="p-3 bg-light rounded-4 border-start border-primary border-5 mb-3 text-start">
                        <h6 class="fw-bold text-primary mb-2">ลักษณะเด่น</h6>
                        <p class="mb-0 small" style="line-height: 1.6;"><?php echo nl2br(htmlspecialchars($cat['characteristics'])); ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if(!empty($cat['care_instructions'])): ?>
                    <div class="p-3 bg-light rounded-4 border-start border-success border-5 mb-4 text-start">
                        <h6 class="fw-bold text-success mb-2">การดูแลรักษา</h6>
                        <p class="mb-0 small" style="line-height: 1.6;"><?php echo nl2br(htmlspecialchars($cat['care_instructions'])); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <div class="text-start">
                        <a href="view.php" class="btn btn-lg rounded-pill text-white shadow-sm" style="background-color: #5c5be5; padding: 12px 40px;">
                            <i class="bi bi-arrow-left me-2"></i>กลับหน้าหลัก
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

} else {
    // ==========================================
    // ส่วนแสดงรายการการ์ด (List Mode)
    // ==========================================
    $sql = "SELECT * FROM CatBreeds ORDER BY id DESC";
    $result = $conn->query($sql);
    ?>
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold display-4" style="color: #2d2d2d;">คลังสายพันธุ์แมว</h2>
            <div class="mx-auto bg-primary mb-3" style="width: 60px; height: 4px; border-radius: 2px;"></div>
            <p class="text-muted fs-5">สำรวจโลกของน้องแมวหลากหลายสายพันธุ์</p>
        </div>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php while($row = $result->fetch_assoc()): ?>
            <div class="col">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-hover">
                    <div style="height: 280px; overflow: hidden;">
                        <img src="<?php echo $upload_path . (!empty($row['image_url']) ? $row['image_url'] : 'no-image.jpg'); ?>" 
                             class="w-100 h-100" 
                             style="object-fit: cover; transition: transform 0.5s;"
                             onmouseover="this.style.transform='scale(1.1)'"
                             onmouseout="this.style.transform='scale(1)'"
                             onerror="this.src='uploads/no-image.jpg'">
                    </div>
                    <div class="card-body p-4 d-flex flex-column text-start">
                        <h4 class="card-title fw-bold mb-2"><?php echo htmlspecialchars($row['name_th']); ?></h4>
                        <p class="card-text text-muted mb-4 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                            <?php echo htmlspecialchars($row['description']); ?>
                        </p>
                        <a href="view.php?id=<?php echo $row['id']; ?>" class="btn text-white w-100 rounded-pill py-2 shadow-sm fw-bold" style="background-color: #5c5be5;">
                            ดูรายละเอียด
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    <style>
        .card-hover { transition: transform 0.3s, box-shadow 0.3s; }
        .card-hover:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }
    </style>
    <?php
}

include 'includes/footer.php'; 
?>