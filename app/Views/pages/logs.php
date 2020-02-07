<div class="container-fluid">
    <div class="container">
        <div class="primary margin-15">
            <div class="row">
                <div class="col-md-8">
                    <div class="col-sm-12 col-md-12 logsClass mb-3"> <label for="email">Active Users</label>
                        <div class="logs-rows bordeBottom">
                            <span>Email</span>
                            <span>Role</span>
                            <span>Ip Adress</span>
                        </div>
                        <?php
                        foreach ($data['loggedUsers'] as $user):
                        ?>
                        <div class="logs-rows">
                            <span><?= $user->email; ?></span>
                            <span><?= $user->role; ?></span>
                            <span><?= $user->ip_adress; ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="col-sm-12 col-md-12 logsClass mb-3"> <label for="email">Logs</label>
                        <div class="logs-rows bordeBottom">
                            <span>Page</span>
                            <span>Ip Address</span>
                            <span>Email</span>
                            <span>Time</span>
                        </div>
                        <?php foreach ($data['log'] as $log): ?>
                            <div class="logs-rows">
                                <span><?= $log->page; ?></span>
                                <span><?= $log->ip_adress; ?></span>
                                <span><?= $log->email; ?></span>
                                <span><?= $log->time_log; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="col-sm-12 col-md-12 logsClass mb-3"> <label for="email">Errors</label>
                        <div class="logs-erors-row bordeBottom">
                            <span>Function</span>
                            <span>Message</span>
                            <span>Time</span>
                        </div>
                        <?php
                        foreach ($data['erorrs'] as $error):
                        ?>
                        <div class="logs-errors">
                            <span><?= $error->error_action; ?></span>
                            <span class="pl-2 pr-2"><?= $error->error_message; ?></span>
                            <span><?= $error->error_time; ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="col-sm-12 col-md-12 logsClass mb-3"> <label for="email">Admin Success</label>
                        <div class="logs-rows bordeBottom">
                            <span>Function</span>
                            <span>Method</span>
                            <span>Email</span>
                            <span>Time</span>
                        </div>
                        <?php foreach ($data['adminSuccess'] as $user): ?>
                            <div class="logs-rows">
                                <span><?= $user->admin_action; ?></span>
                                <span><?= $user->admin_desc; ?></span>
                                <span><?= $user->email; ?></span>
                                <span><?= $user->admin_time; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="col-sm-12 col-md-12 logsClass mb-3"> <label for="email">Comments</label>
                        <div class="logs-rows bordeBottom">
                            <span>Comment</span>
                            <span>Mthod</span>
                            <span>Email</span>
                            <span>Time</span>
                        </div>
                        <?php foreach ($data['commentsLogs'] as $user): ?>
                            <div class="logs-rows">
                                <span><?= $user->comment; ?></span>
                                <span><?= $user->comment_action; ?></span>
                                <span><?= $user->email; ?></span>
                                <span><?= $user->comment_time; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!--Start Sidebar-->

