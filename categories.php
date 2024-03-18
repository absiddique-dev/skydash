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
    <div class="container bg-white shadow rounded p-2 mb-5">

      <h1 class="h4"><?= $edit ? "Update" : "Add" ?> New Categories</h1>
      <div class="table-responsive">
        <form action="" method="POST" class="d-flex flex-wrap align-items-center" enctype="multipart/form-data">

          <div class="col-md-4 col-sm-6 mb-3">
            <label for="title">Title</label>
            <input type="text" placeholder="Title" class="form-control" name="title" id="title" value="<?= $catdata['title'] ?? "" ?>" required>
          </div>

          <div class="col-md-4 col-sm-6 mb-3">
            <label for="slug">Slug</label>

            <sub class="text-danger"><?= $edit ? 'slug is not editable' : '' ?></sub>
            <input type="text" placeholder="Slug" class="form-control" name="slug" id="slug" value="<?= $catdata['slug'] ?? "" ?>" required <?= $edit ? 'disabled' : '' ?>>
          </div>

          <div class="col-md-4 col-sm-6 mb-3">
            <label for="position">Position</label>
            <input type="text" placeholder="Position" class="form-control" name="position" value="<?= $catdata['position'] ?? "" ?>" required>
          </div>

          <div class="col-md-4 col-sm-6 mb-3">
            <label for="status">Status</label>
            <select name="status" class="form-control">
              <option value="active" <?= $edit && $catdata['status'] == "active" ? "selected" : '' ?>>Active</option>
              <option value="inactive" <?= $edit && $catdata['status'] == "inactive" ? "selected" : '' ?>>Inactive</option>
            </select>

          </div>

          <div class="col-md-4 col-sm-6 mb-3">
            <label for="image">Image</label>
            <input type="file" class="form-control" name="image" <?= $edit ? '' : 'required' ?>>
          </div>

          <div class="col-md-4 col-sm-12 mb-3 d-flex flex-column">
            <label for="image">Action</label>

            <button class="btn btn-text btn-dark" name="<?= $edit ? "update" : "submit" ?>">
              <i class="ti-upload btn-icon-prepend pr-2"></i> <span><?= $edit ? "Update" : "Upload" ?> Categories</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <div class="container bg-white shadow rounded p-2">
      <h1 class="h4">Add Sub Categories</h1>
      <div class="table-responsive">
        <form action="" method="POST" class="d-flex flex-wrap align-items-center" enctype="multipart/form-data">
          <div class="col-md-4 col-sm-6 mb-3">
            <label for="category_id">Category</label>
            <select name="category_id" class="form-control">
              <option value="">Select Category</option>
              <?php
              $categories = mysqli_query($conn, "SELECT * FROM categories WHERE status = 'active'");
              while ($data = mysqli_fetch_array($categories)) {
              ?>
                <option value="<?= $data['id'] ?>" <?= isset($_GET['category_id']) && $_GET['category_id'] == $data['id'] ? 'selected' : '' ?>><?= $data['title'] ?></option>
              <?php
              }
              ?>
            </select>
          </div>
          <div class="col-md-4 col-sm-6 mb-3">
            <label for="sub_category_title">Sub category Title</label>
            <input type="text" placeholder="Sub category title" class="form-control" name="sub_category_title" id="sub_category_title" value="<?= $catdata['title'] ?? "" ?>" required>
          </div>
          <div class="col-md-4 col-sm-6 mb-3">
            <label for="sub_category_slug">Slug</label>
            <input type="text" placeholder="Slug" class="form-control" name="sub_category_slug" id="sub_category_slug" value="<?= $catdata['slug'] ?? "" ?>" required>
          </div>
          <!-- for slug  -->
          <script>
            function slugify(e) {
              return e.toString().toLowerCase().replace(/\s+/g, "-").replace(/[^\w\-]+/g, "").replace(/\-\-+/g, "-").replace(/^-+/, "").replace(/-+$/, "")
            }
            document.getElementById("sub_category_title").addEventListener("input", function() {
              document.getElementById("sub_category_slug").value = slugify(this.value)
            });
          </script>

          <div class="col-md-4 col-sm-12 mb-3 d-flex flex-column">
            <label for="submit_sub_category">Action</label>
            <button class="btn btn-text btn-dark" name="submit_sub_category">
              Submit
            </button>
          </div>

        </form>
      </div>

    </div>
    <!-- // category submit & update  -->
    <?php
    if (isset($_POST['submit'])) {
      $title = $_POST['title'];
      $slug = $_POST['slug'];
      $position = $_POST['position'];
      $status =  $_POST['status'];

      $checkSlug = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM categories WHERE slug = '$slug'"));

      if ($checkSlug == 0) {
        if ($_FILES["image"]['tmp_name']) {
          $image = $_FILES['image'];
          $filename = $image['name'];
          $fileerror = $image['error'];
          $filetmp = $image['tmp_name'];
          $fileext = explode('.', $filename);
          $filecheck = strtolower(end($fileext));
          $fileextstored = array('png', 'jpg', 'jpeg');

          if (in_array($filecheck, $fileextstored)) {
            $destination = 'images/categories/' . $filename;
            move_uploaded_file($filetmp, $destination);
          }
        } else {
          $destination = $catdata['img_url'];
        }

        $addcat = mysqli_query($conn, "INSERT INTO categories SET title = '$title' , slug = '$slug' , position = '$position' , status = '$status' , img_url = '$destination'");

    ?>
        <script>
          location.replace('categories.php');
        </script>
      <?php
      } else {
      ?>
        <script>
          alert("Slug is already exists");
        </script>
      <?php
      }
    }

    // for update categories 
    if (isset($_POST['update'])) {
      $title = $_POST['title'];
      $position = $_POST['position'];
      $status =  $_POST['status'];

      if ($_FILES["image"] && $_FILES['image']['tmp_name']) {
        $image = $_FILES['image'];
        $filename = $image['name'];
        $fileerror = $image['error'];
        $filetmp = $image['tmp_name'];
        $fileext = explode('.', $filename);
        $filecheck = strtolower(end($fileext));
        $fileextstored = array('png', 'jpg', 'jpeg');

        if (in_array($filecheck, $fileextstored)) {
          $destination = 'images/categories/' . $filename;
          move_uploaded_file($filetmp, $destination);
        }
      } else {
        $destination = $catdata['img_url'];
      }

      $updaterun = mysqli_query($conn, "UPDATE categories SET title = '$title', position = '$position', status = '$status' , img_url = '$destination' WHERE id = '$catdata[id]'");

      ?>
      <script>
        location.replace('categories.php');
      </script>
    <?php
    }

    ?>
    <!-- sub category submit  -->
    <?php
    //submit 
    if (isset($_POST['submit_sub_category'])) {
      $category_id = $_POST['category_id'];
      $sub_category_title = $_POST['sub_category_title'];
      $sub_category_slug = $_POST['sub_category_slug'];
      $checkSlug = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM sub_categories WHERE slug = '$sub_category_slug' AND category_id = '$category_id' "));

      if ($checkSlug == 0) {
        $add = mysqli_query($conn, "INSERT INTO sub_categories SET  category_id = '$category_id', title = '$sub_category_title', slug = '$sub_category_slug'");
      } else {
    ?>
        <script>
          alert("Sub category already exist on this category")
        </script>
    <?php
      }
    }
    ?>

    <div class="container bg-white shadow mt-5 p-3 rounded">
      <h1 class="h4">All Categories</h1>
      <div class="table-responsive">
        <table class="table" id="myTable">
          <thead>
            <tr>
              <th>#</th>
              <th>Image</th>
              <th>Category</th>
              <th>Sub Category</th>

              <th>Position</th>
              <th>Created at</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $serial = 1;
            $qrun = mysqli_query($conn, "SELECT * FROM categories ORDER BY id DESC");
            while ($data = mysqli_fetch_array($qrun)) {
              $numSubCategories = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM sub_categories WHERE category_id = '$data[id]' "))
            ?>
              <tr valign="middle">
                <td><?= $serial++ ?></td>
                <td><img src="<?= $data['img_url'] ?>" alt="" style="width: 75px; height: 75px; object-fit: cover"></td>
                <td><?= $data['title'] ?></td>
                <td>Available : <?= $numSubCategories ?></td>
                <td><?= $data['position'] ?></td>
                <td><?= $data['created_at'] ?></td>
                <td>
                  <button class="btn btn-danger p-2" onclick="deletecategories(<?= $data['id'] ?>)">delete</button>
                  <a href="?edit=<?= $data['id'] ?>" class="btn btn-info px-2 py-2"><i style="padding-left: 7px;" class="fa-regular fa-pen-to-square ps-5"></i></a>
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

  <script>
    // Function to generate slug from title
    function generateSlug(title) {
      return title.trim() // Remove leading and trailing whitespaces
        .toLowerCase() // Convert to lowercase
        .replace(/\s+/g, '-') // Replace spaces with dashes
        .replace(/[^\w-]/g, ''); // Remove non-word characters except dashes
    }

    // Function to update slug input field
    function updateSlug() {
      var titleInput = document.getElementById('title');
      var slugInput = document.getElementById('slug');

      var titleValue = titleInput.value;
      var slugValue = generateSlug(titleValue);

      slugInput.value = slugValue;
    }

    // Add event listener to title input field
    document.getElementById('title').addEventListener('input', updateSlug);
  </script>