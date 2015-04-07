<div class="share">
    <div class="profile-photo pull-left">
       <?php echo CHtml::image("https://secure.gravatar.com/avatar/" . md5( strtolower( trim( $user->email ) ) ) . "?&s=50&d=mm"); ?>
    </div>
    <div class="pull-left">
           <div><?php echo "$user->name (" . CHtml::link("@$user->username", $this->createUrl('timeline/index/'.$user->username)) .")"; ?></div>
           <div class="items">
               <strong>Followers:</strong> <?php echo $user->followeesCount; ?>
               <strong>Following:</strong> <?php echo $user->followersCount; ?>
               <strong>Shares:</strong> <span class="share-count"><?php echo $user->sharesCount; ?>
           </div>
    </div>
    <div class="clearfix"></div>
</div>
