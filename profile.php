<?php
include 'app/bootstrap.php';
$profileId = (int)($_GET['profileId'] ? $_GET['profileId'] : $loggedInId);


$profileData = $conn->query("SELECT * FROM `user` WHERE `id` = '$profileId';")[0];
$comments = $conn->query("SELECT `from_user`.`name`, `from_user`.`id`, `from_user`.`image`, `comment`.`text` FROM `user_comment` LEFT JOIN `user` AS `from_user` ON `user_comment`.`from_user` = `from_user`.`id` LEFT JOIN `comment` ON `user_comment`.`comment_id` = `comment`.`id` WHERE `user_comment`.`to_user` = '$profileId';");
$likeNumber = $conn->query("SELECT COUNT(`to_user`) AS `count` FROM `likes` WHERE `to_user` = '$profileId';")[0]['count'];

function wasLiked($targetUser = false) {
    global $conn, $loggedInId, $profileId;
    $targetUser = $targetUser ? $targetUser : $profileId;
    $result = $conn->connection->query("SELECT * FROM likes WHERE `from_user` = '$loggedInId' AND `to_user` = '$targetUser'");
    return ($result->num_rows ? true : false);
}

$meta_title = $loggedUser['name'];
include 'app/components/header.php'
?>
<div class="container">
  <div class="row my-2">
    <div class="col-lg-4 text-center">
      <img src="<?php echo $profileData['image'] ?>" class="mx-auto mt-5 profile-image" alt="avatar">
    </div>
    <div class="col-lg-8">
      <ul class="nav nav-tabs">
        <li class="nav-item">
          <a href="" data-target="#profile" data-toggle="tab" class="nav-link active">Profile</a>
        </li>
          <?php if ($profileId === $loggedInId) { ?>
            <li class="nav-item">
              <a href="" data-target="#edit" data-toggle="tab" class="nav-link">Edit</a>
            </li>
          <?php } ?>
      </ul>
      <div class="tab-content py-4">
        <div class="tab-pane active" id="profile">
          <h5 class="mb-3"><?php echo $profileData['name'] ?>, <?php echo $profileData['age'] ?></h5>
          <div class="row">
            <div class="col-md-6">
              <h6>About</h6>
                <?php echo $profileData['bio'] ?>
            </div>
            <div class="col-md-6">
              <div class="d-flex align-items-center">
                <span class="badge badge-danger"><i class="fa fa-heart"></i> <?php echo $likeNumber ?> Likes</span>
                  <?php if (wasLiked()) { ?>
                    <a href="app/actions/like.php?userId=<?php echo $profileId ?>" class="ml-2 btn btn-sm btn-danger">Like!</a>
                  <?php } else { ?>
                    <a href="app/actions/like.php?userId=<?php echo $profileId ?>" class="ml-2 btn btn-sm btn-outline-danger">Like!</a>
                  <?php } ?>
              </div>
            </div>
            <div class="col-md-12">
              <h5 class="mt-5">Comments</h5>
                <?php if ($loggedInId !== $profileId) { ?>
                  <form action="app/actions/comment.php" method="post" class="mb-3">
                    <div class="input-group">
                      <textarea class="form-control" aria-label="With textarea" name="text" style="resize: none" rows="2"></textarea>
                      <input type="hidden" name="to_user" value="<?php echo $profileId ?>">
                      <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">Send</button>
                      </div>
                    </div>
                  </form>
                <?php } ?>
              <table class="table table-sm table-hover table-striped">
                <tbody>
                <?php foreach ($comments as $comment) { ?>
                  <tr>
                    <td>
                      <div class="media">
                        <a href="profile.php?profileId=<?php echo $comment['id'] ?>">
                          <img class="mr-3 profile-image image-sm" src="<?php echo $comment['image'] ?>" alt="<?php echo $comment['name'] ?>">
                        </a>
                        <div>
                          <a href="profile.php?profileId=<?php echo $comment['id'] ?>">
                            <h6 class="mt-0 mb-1"><?php echo $comment['name'] ?></h6>
                          </a>
                          <p class="media-body">
                              <?php echo $comment['text'] ?>
                          </p>
                        </div>
                      </div>
                    </td>
                  </tr>
                <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
          <!--/row-->
        </div>
          <?php if ($profileId === $loggedInId) { ?>
            <div class="tab-pane" id="edit">
              <form role="form" action="app/actions/editProfile.php" method="post">
                <div class="form-group row">
                  <label class="col-lg-3 col-form-label form-control-label">Name</label>
                  <div class="col-lg-9">
                    <input class="form-control" type="text" name="name"  value="<?php echo $profileData['name'] ?>" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-lg-3 col-form-label form-control-label">Age</label>
                  <div class="col-lg-9">
                    <input class="form-control" type="number" name="age" value="<?php echo $profileData['age'] ?>" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-lg-3 col-form-label form-control-label">Bio</label>
                  <div class="col-lg-9">
                    <textarea class="form-control" rows="3" name="bio" required><?php echo $profileData['bio'] ?></textarea>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-lg-3 col-form-label form-control-label">External image link</label>
                  <div class="col-lg-9">
                    <input class="form-control" type="url" name="image" value="<?php echo $profileData['image'] ?>" required>
                  </div>
                </div>
                <div class="text-right">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
          <?php } ?>
      </div>
    </div>
  </div>
</div>
<?php include 'app/components/footer.php' ?>
