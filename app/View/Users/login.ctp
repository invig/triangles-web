<!--nocache-->
<div class="users form row">
    <div class="columns small-12 th" style="margin-top:10px; padding:20px;">
        <h1 style="margin-bottom:20px;">Login</h1>
        <?php echo $this->Session->flash('auth'); ?>
        <?php echo $this->Form->create('User'); ?>
            <?php echo $this->Form->input('email');
            echo $this->Form->input('password');
            ?>
        <?php echo $this->Form->end(__('Login')); ?>
    </div>
</div>
<!--/nocache-->