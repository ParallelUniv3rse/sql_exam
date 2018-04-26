<?php
include 'app/bootstrap.php';
$profileList = $conn->query("SELECT * FROM `user`");
?>
<?php include 'app/components/header.php' ?>
<section class="jumbotron text-center">
  <div class="container">
    <h1 class="jumbotron-heading">Get love tonight!</h1>
    <p class="lead text-muted">All these heroes and heroines in the area are waiting just for you!</p>
  </div>
</section>

<div class="album py-5 bg-light">
  <div class="container">
    <div class="row">
        <?php foreach ($profileList as $profile) { ?>

          <div class="col-md-4">
            <div class="card mb-4 box-shadow">
              <div class="card-body">
                <h2><?php echo $profile['name'] ?></h2>
                <p class="card-text"><?php echo $profile['bio'] ?></p>
                <div>
                  <a href="profile.php?profileId=<?php echo $profile['id'] ?>" class="btn btn-outline-primary ">View profile</a>
                </div>
                  <?php if ($loggedInId !== (int)$profile['id']) {?>
                    <div class="muted mt-3">
                      <a href="app/actions/switchUser.php?profileId=<?php echo $profile['id'] ?>">log in as <?php echo $profile['name'] ?></a>
                    </div>
                  <?php } ?>
              </div>
            </div>
          </div>
        <?php } ?>
    </div>
  </div>
</div>

<?php include 'app/components/footer.php' ?>
