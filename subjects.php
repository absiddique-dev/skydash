<?php
include("./layout/header.php");
// $numCatagories = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM categories"));

?>

<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper col-md-12 col-lg-12">
        <div class="container bg-white shadow rounded p-2">

            <h1 class="h4">Add Subjects</h1>
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
                                <option class="subject_option" data-category="<?= $subCategoriesData['category_id'] ?>" value="<?= $subCategoriesData['id'] ?>"><?= $subCategoriesData['title'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <script>
                        document.getElementById('category_id').addEventListener('change', function() {
                            var selectedCategory = this.value;
                            var subjectOptions = document.querySelectorAll('.subject_option');
                            var subjectSelect = document.getElementById('sub_category_id');

                            // Reset subject select dropdown to default state
                            subjectSelect.selectedIndex = 0;

                            subjectSelect.disabled = selectedCategory === '';

                            subjectOptions.forEach(function(option) {
                                var category = option.getAttribute('data-category');
                                option.style.display = (selectedCategory === '' || selectedCategory === category) ? 'block' : 'none';
                            });
                        });
                    </script>

                    <div class="col-md-4 col-sm-6 mb-3">
                        <label for="subject_name">Subject Name</label>
                        <input type="text" placeholder="Subject name" class="form-control" name="subject_name" id="title" value="<?= $catdata['title'] ?? "" ?>" required>
                    </div>
                    <div class="col-md-4 col-sm-12 mb-3 d-flex flex-column">
                        <label for="image">Action</label>
                        <button class="btn btn-text btn-dark" name="submit">
                            Submit
                        </button>
                    </div>

                </form>
            </div>

            <?php
            //submit 
            if (isset($_POST['submit'])) {
                $subject_name = $_POST['subject_name'];
                $sub_category_id = $_POST['sub_category_id'];
                $addcat = mysqli_query($conn, "INSERT INTO subjects SET   sub_category_id = '$sub_category_id', subject_name = '$subject_name'");
            }
            ?>
        </div>
        <div class="container bg-white shadow mt-5 p-3 rounded">
            <h1 class="h4">All Subjects</h1>
            <div class="table-responsive">
                <table class="table" id="myTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Subject Name</th>
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Created at</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $serial = 1;
                        $query = "SELECT * FROM subjects ORDER BY id DESC";
                        $qrun = mysqli_query($conn, $query);
                        while ($data = mysqli_fetch_array($qrun)) {
                            $sub_category_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM sub_categories WHERE id = '$data[sub_category_id]'"));
                            $category_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM categories WHERE id = '$sub_category_name[category_id]'"));
                       
                       ?>
                            <tr valign="middle">
                                <td><?= $serial++ ?></td>
                                <td><?= $data['subject_name'] ?></td>
                                <td><?= $category_name['title'] ?></td>
                                <td><?= $sub_category_name['title'] ?></td>
                                <td><?= $data['created_at'] ?></td>
                                <td>
                                    <button class="btn" onclick="deleteRequest(<?= $data['id'] ?>)">
                                        <i class="fa-solid fa-trash text-danger pl-1" style="font-size: 21px;"></i>
                                    </button>
                                </td>

                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <?php
    include("./layout/footer.php");
    ?>
    <script>
        let table = new DataTable('#myTable');

        async function deleteRequest(id) {


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

                    const request = await fetch('delete/deletesubject.php', {
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
                        'Subject has been deleted.',
                        'success'
                    ).then((result) => {
                        if (result.isConfirmed) {
                            location.replace("subjects.php");
                        }
                    })
                }
            })
        }
    </script>