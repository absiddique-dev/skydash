<?php
include("./layout/header.php");
$numCatagories = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM categories"));

if (isset($_GET['edit'])) {
    $edit = true;
    $catdata = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM categories WHERE  id = '$_GET[edit]'"));
} else {
    $edit = false;
}

?>

<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper col-md-12 col-lg-12">
        <div class="container bg-white shadow rounded p-2">

            <h1 class="h4"><?= $edit ? "Update" : "Add" ?> Study Material</h1>
            <div class="table-responsive">
                <form action="" method="POST" class="d-flex flex-wrap align-items-center" enctype="multipart/form-data">
                    <div class="col-md-4 col-sm-6 mb-3">
                        <label for="category_id">Category</label>
                        <select id="category_id" name="category_id" class="form-control">
                            <option value="">Select Category</option>
                            <?php
                            $categories = mysqli_query($conn, "SELECT * FROM categories WHERE status = 'active'");
                            while ($data = mysqli_fetch_array($categories)) {
                            ?>
                                <option value="<?= $data['id'] ?>"><?= $data['title'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4 col-sm-6 mb-3">
                        <label for="sub_category_id">Sub Category</label>
                        <select id="sub_category_id" name="sub_category_id" class="form-control" disabled>
                            <option value="">Sub Category</option>
                            <?php
                            $subCategories = mysqli_query($conn, "SELECT * FROM sub_categories");
                            while ($subCategoriesData = mysqli_fetch_array($subCategories)) {
                            ?>
                                <option class="sub_category_option" data-category="<?= $subCategoriesData['category_id'] ?>" value="<?= $subCategoriesData['id'] ?>"><?= $subCategoriesData['title'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4 col-sm-6 mb-3">
                        <label for="subject_id">Subject Name</label>
                        <select id="subject_id" name="subject_id" class="form-control" disabled>
                            <option value="">Select Subject</option>
                            <?php
                            $allSubjects = mysqli_query($conn, "SELECT * FROM subjects");
                            while ($subjectData = mysqli_fetch_array($allSubjects)) {
                            ?>
                                <option class="subject_option" data-subcategory="<?= $subjectData['sub_category_id'] ?>" value="<?= $subjectData['id'] ?>"><?= $subjectData['subject_name'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <script>
                        document.getElementById('category_id').addEventListener('change', function() {
                            var selectedCategory = this.value;
                            var subCategorySelect = document.getElementById('sub_category_id');
                            var subjectSelect = document.getElementById('subject_id');

                            // Reset subcategory and subject select dropdowns to default state
                            subCategorySelect.selectedIndex = 0;
                            subjectSelect.selectedIndex = 0;

                            // Disable subcategory and subject select dropdowns until both category and subcategory are selected
                            subCategorySelect.disabled = selectedCategory === '';
                            subjectSelect.disabled = true;

                            // Hide all subcategory and subject options
                            Array.from(subCategorySelect.options).forEach(function(option) {
                                option.style.display = 'none';
                            });
                            Array.from(subjectSelect.options).forEach(function(option) {
                                option.style.display = 'none';
                            });

                            // Show subcategory options corresponding to the selected category
                            Array.from(subCategorySelect.options).forEach(function(option) {
                                if (option.getAttribute('data-category') === selectedCategory || selectedCategory === '') {
                                    option.style.display = 'block';
                                }
                            });
                        });

                        document.getElementById('sub_category_id').addEventListener('change', function() {
                            var selectedSubCategory = this.value;
                            var subjectSelect = document.getElementById('subject_id');

                            // Reset subject select dropdown to default state
                            subjectSelect.selectedIndex = 0;

                            // Disable subject select dropdown until both category and subcategory are selected
                            subjectSelect.disabled = selectedSubCategory === '';

                            // Hide all subject options
                            Array.from(subjectSelect.options).forEach(function(option) {
                                option.style.display = 'none';
                            });
                            // Show subject options corresponding to the selected category and subcategory
                            Array.from(subjectSelect.options).forEach(function(option) {
                                if ((option.getAttribute('data-subcategory') === selectedSubCategory || selectedSubCategory === '')) {
                                    option.style.display = 'block';
                                }
                            });
                        });
                    </script>

                    <div class="col-md-4 col-sm-6 mb-3">
                        <label for="title">Title</label>
                        <input type="text" placeholder="Title" class="form-control" name="title" id="title" value="<?= $catdata['title'] ?? "" ?>" required>
                    </div>
                    <div class="col-md-4 col-sm-6 mb-3">
                        <label for="status">Status</label>
                        <!-- <input type="text" placeholder="Status" class="form-control" name="position" value="<?= $catdata['position'] ?? "" ?>" required> -->
                        <select name="status" class="form-control">
                            <option value="active" <?= $edit && $catdata['status'] == "active" ? "selected" : '' ?>>Active</option>
                            <option value="inactive" <?= $edit && $catdata['status'] == "inactive" ? "selected" : '' ?>>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-12 col-sm-12 mb-3">
                        <label for="description">Description</label>
                        <textarea name="description" id="mytextarea" cols="30" rows="10" class="form-control"></textarea>
                        <!-- <input type="text" class="form-control" name="desc" <?= $edit ? '' : 'required' ?>> -->
                    </div>
                    <div class="col-md-4 col-sm-12 mb-3 d-flex flex-column">
                        <label for="image">Action</label>
                        <button class="btn btn-text btn-dark" name="<?= $edit ? "update" : "submit" ?>">
                            <i class="ti-upload btn-icon-prepend pr-2"></i> <span><?= $edit ? "Update" : "Submit" ?></span>
                        </button>
                    </div>

                </form>
            </div>


            <?php
            //submit 
            if (isset($_POST['submit'])) {
                $title = $_POST['title'];
                $category_id = $_POST['category_id'];
                $sub_category_id = $_POST['sub_category_id'];
                $subject_id = $_POST['subject_id'];
                $description = $_POST['description'];
                $status =  $_POST['status'];

                $add = mysqli_query($conn, "INSERT INTO study_material SET  category_id = '$category_id', sub_category_id = '$sub_category_id' , subject_id = '$subject_id', title = '$title' , description = '$description' , status = '$status'");
            }
            ?>
        </div>
    </div>
    <?php
    include("./layout/footer.php");
    ?>
    <script>
        let table = new DataTable('#myTable');

        async function deletecategories(id) {


            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then(async (result) => {
                if (result.isConfirmed) {

                    const request = await fetch('delete/deletecategories.php', {
                        method: "POST",
                        header: {
                            "Content-type": "application/json"
                        },
                        body: JSON.stringify({
                            id
                        })
                    });

                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    ).then((result) => {
                        if (result.isConfirmed) {
                            location.replace("categories.php");
                        }
                    })
                }
            })
        }
    </script>
