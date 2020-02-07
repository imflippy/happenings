<div class="container-fluid">
    <div class="container">
        <div class="primary margin-15">
            <div class="row">
                <div class="col-md-8">
                    <form>
                        <input type="hidden" id="hiddenUser">
                        <div class="comment-form-email col-sm-12 col-md-12 mt-2 mb-2">
                            <label for="email">Email (*)</label> <input type="email" id="emailAdd"  placeholder="Email *" value="" size="30" class="black">
                        </div>
                        <div class="comment-form-author col-sm-12 col-md-12 passwords mt-2 mb-2">
                            <label for="author">Password (*)</label> <input type="password" id="passwordAdd" placeholder="Your password *" value="" class="black">
                        </div>
                        <div class="comment-form-author col-sm-12 col-md-12 passwords mt-2 mb-2">
                            <label>Confirm Password (*)</label> <input type="password" id="confirm_passwordAdd"  placeholder="Confirm password *" value="" class="black">
                        </div>
                        <div class="comment-form-author col-sm-6 col-md-6 mt-3 mb-3">
                            <label>Choose Role (*)</label>
                            <div class="radioButtons">
                                <?php
                                foreach ($data['roles'] as $role):
                                    ?>
                                    <span> <input type="radio" value="<?= $role->id_role; ?>" name="role"><?= $role->role; ?></span>

                                <?php  endforeach; ?>
                            </div>

                        </div>
                        <div class="comment-form-author col-sm-6 col-md-6 mt-3 mb-3">
                            <label>Choose Activity (*)</label>
                            <div class="radioButtons">
                                <span><input type="radio" value="0" name="activity">Inactive</span>
                                <span><input type="radio" value="1" name="activity">Active</span>
                            </div>
                        </div>

                        <p class="form-submit pt-4">
                            <input type="button" id="btnAddUser" class="submit customSubmit" value="Add User">
                            <input type="button" id="btnClearFormUser" class="submit customSubmit" value="Clear Form">
                        </p>
                    </form>

                    <div>

                        <div class="comment-form-email col-sm-12 col-md-12 mt-5">
                            <label for="email">Search for user</label> <input type="text" id="serchUser"  placeholder="User Email" value="" size="30" class="black">
                        </div>
                    <table id="allUsers">
                        <tr>
                            <td>Id User</td>
                            <td>Email</td>
                            <td>Activity</td>
                            <td>Role</td>
                        </tr>


                    </table>

                    </div>
                </div>
                <!--Start Sidebar-->

