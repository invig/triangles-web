<!--nocache-->
<div class="users form row">
    <div class="columns small-12 th cakephp" style="margin-top:10px; padding:20px;">
        <h1 style="margin-bottom:20px;">Waiting List</h1>
        <?php if (isset($thanks) && $thanks == true) : ?>
            <p>Added. The waiting list is length is currently: <?php echo $position; ?></p>
        <?php else: ?>
            <p>Triangles isn't running on large infrastructure yet, join the list and we'll add you once we've scaled up!</p>
            <?php
            echo $this->Form->create("Waiter");
            echo $this->Form->input("email");
            echo $this->Form->end(__('Submit'));
        ?>
        <?php endif; ?>
    </div>
</div>
<!--/nocache-->
