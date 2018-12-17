<?php
    $this->assign('title', '管理者');
    echo $this->Html->charset();
    echo $this->Html->css(['profile', 'components']);
    echo $this->Html->script(['moment', 'daterangepicker']);
    $user->callerId=str_replace('+81', '0', $user->callerId)
?>
<?php echo $this->Flash->render() ?>
<div class="content-bar">
    <h2><span>PROFILE</span>プロフィール</h2>
</div>
<div class="content row">
    <div class="col-md-8">
        <?= $this->Form->create(
            $user,
            [
                'type'=>'file',
                'url' => 
                [
                    'action' => 'edit'
                ],
                'id'=>'user-change'
                ]
            ) 
            ?>
        <div class="panel fade in panel-default panel-fill">
            <div class="panel-heading">
                <div class="pull-left mr-2x">
                    <span class="kit-avatar kit-avatar-64">
                        <?= $this->Html->image(
                            'user/'.$user->image, 
                            [
                                'autocomplete'=>'off',
                                'alt' => 'user image', 
                                'class'=>'content-user-image media-object'
                            ]);
                        ?>
                    </span>
                </div>
                <p class="lead mb-1x"><?= $user->access==1?'<i class="fa fa-star color _red" title="admin"></i>':'<i class="fa fa-user-circle-o" title="member"></i>' ?> <?= $user->name ?></p>
                <p class="text-muted"><i class="fa fa-home"></i>
                    <?= $user->company ?>
                    <?= $this->Form->control('access',['type'=>'hidden']) ?>
                </p>
            </div>
            <div class="panel-body col-md-6">
                <h3><i class="fa fa-info"></i> 個人情報</h3>
                <div class="form-group">
                    <label class="control-label">名前 
                        <span class="color _red" title="You can edit this content">(<i class="fa fa-pencil"></i>)</span>
                    </label>
                    <div class="input-group input-group-in">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <?= $this->Form->text(
                            'name',
                            [
                                'class' => 'form-control',
                                'value' => $user->name
                            ]
                        )
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">生年月日 
                        <span class="color _red" title="You can edit this content">(<i class="fa fa-pencil"></i>)</span>
                    </label>
                    <div class="input-group input-group-in">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <?= $this->Form->text(
                            'birthday',
                            [
                                'data-input' => 'daterangepicker',
                                'data-single-date-picker' => 'true',
                                'data-show-dropdowns' => 'true',
                                'class' => 'form-control',
                                'value' => date('Y/m/d', strtotime($user->birthday))
                            ]
                        )
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">性別 
                        <span class="color _red" title="You can edit this content">(<i class="fa fa-pencil"></i>)</span>
                    </label><br>
                    <div class="nice-radio nice-radio-inline">
                        <input class="radio-o" type="radio" name="gender" id="male" <?php if($user->gender==0){?> checked="checked" <?php }?> value="0">
                        <label for="gender-0">男性</label>
                    </div>
                    <div class="nice-radio nice-radio-inline">
                        <input class="radio-o" type="radio" name="gender" id="female" <?php if($user->gender==1){?> checked="checked" <?php }?> value="1">
                        <label for="gender-1">女性</label>
                      </div>
                </div>
                <div class="form-group">
                    <label class="control-label">メールアドレス</label>
                    <div class="input-group input-group-in">
                        <span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
                        <?= $this->Html->link(
                            $user->email,
                            'mailto:'.$user->email,
                            [
                                'class' => 'form-control'
                            ]
                        ) 
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">住所 
                        <span class="color _red" title="You can edit this content">(<i class="fa fa-pencil"></i>)</span>
                    </label>
                    <div class="input-group input-group-in">
                        <span class="input-group-addon"><i class="fa fa-address-card-o"></i></span>
                        <?= $this->Form->text(
                            'address',
                            [
                                'class' => 'form-control',
                                'value' => $user->address
                            ]
                        )
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">電話番号 
                        <span class="color _red" title="You can edit this content">(<i class="fa fa-pencil"></i>)</span>
                    </label>
                    <div class="input-group input-group-in">
                        <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                        <?= $this->Form->text(
                            'phone',
                            [
                                'class' => 'form-control number',
                                'value' => $user->phone
                            ]
                        )
                        ?>
                    </div>
                </div>
            </div>
            <div class="panel-body col-md-6">
                <h3><i class="fa fa-id-card-o"></i> アカウントデータ</h3>
                <div class="form-group">
                    <label class="control-label">アカウントSID</label>
                    <div class="input-group input-group-in">
                        <span class="input-group-addon">SID</span>
                        <?= $this->Form->text(
                            'none',
                            [
                                'class' => 'form-control',
                                'value' => $user->accountSid,
                                'disabled' => 'disabled'
                            ]
                        )
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">認証トークン</label>
                    <div class="input-group input-group-in">
                        <span class="input-group-addon"><i class="fa fa-key"></i></span>
                        <?= $this->Form->password(
                            'none',
                            [
                                'class' => 'form-control',
                                'value' => $user->authToken,
                                'disabled' => 'disabled'
                            ]
                        )
                        ?>
                        <?= $this->Html->link(
                            '<i class="fa fa-eye-slash"></i>',
                            'javascript:void(0)',
                            [
                                'class' => 'input-group-addon show-pass',
                                'escape' => false
                            ]
                        ) 
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">アプリケーションID</label>
                    <div class="input-group input-group-in">
                        <span class="input-group-addon">ID</span>
                        <?= $this->Form->text(
                            'none',
                            [
                                'class' => 'form-control',
                                'value' => $user->appSid,
                                'disabled' => 'disabled'
                            ]
                        )
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">センター番号</label>
                    <div class="input-group input-group-in">
                        <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                        <?= $this->Form->text(
                            'none',
                            [
                                'class' => 'form-control',
                                'value' => $user->callerId,
                                'disabled' => 'disabled'
                            ]
                        )
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">アバター画像 (Jpgファイル、500Kb未満) 
                        <span class="color _red" title="You can edit this content">(<i class="fa fa-pencil"></i>)</span>
                    </label>
                    <div class="input-group input-group-in">
                        <span class="input-group-addon"><i class="fa fa-upload"></i></span>
                        <span class="upload-file form-control">
                            <span>Choose file...</span>
                            <?= $this->Form->input(
                                'img', 
                                [
                                    'type' => 'file',
                                    'label'=>false,
                                    'data-trigger' => 'fileinput',
                                    'templates' => ['inputContainer' => '{{content}}']
                                ]
                            )
                            ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="form-group pull-right">
                    <?= $this->Form->button(
                        '保存',
                        [
                            'id'=> 'user-change-button',
                            'class' => 'button _big _green'
                        ]
                    ) 
                    ?>
                </div>
            </div>
        </div>
    <?= $this->Form->end() ?>
    </div>
    <div class="col-md-4">
        <?= $this->Form->create(
            'log', 
            [
                'url'=>
                [
                    'action'=>'/changePass'
                ], 
                'id'=>'user-change-pass'
            ]
        )
        ?>
        <div class="panel fade in panel-default" data-init-panel="true">
            <div class="panel-heading">
                <h3 class="panel-title">Change Password</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label">現在のパスワード</label>
                    <div class="input-group input-group-in">
                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                        <?= $this->Form->password(
                            'oldPassword', 
                            [
                                'class' => 'form-control'
                            ]
                        )
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">新しいパスワード</label>
                    <div class="input-group input-group-in">
                        <span class="input-group-addon"><span class="dotted"></span></span>
                        <?= $this->Form->password(
                            'newPassword', 
                            [
                                'class' => 'form-control',
                                'id' => 'newpassword'
                            ]
                        )
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">確認のためにもう一度入力してください</label>
                    <div class="input-group input-group-in">
                        <span class="input-group-addon"><span class="dotted"></span></span>
                        <?= $this->Form->password(
                            'confirmPassword', 
                            [
                                'class' => 'form-control'
                            ]
                        )
                        ?>
                    </div>
                </div>
                <div class="form-group pull-right">
                    <?= $this->Form->button(
                        '保存',
                        [
                            'id' => 'pass-change-button',
                            'class' => 'button _big _green'
                        ]   
                    ) 
                    ?>
                </div>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>
<!-- Jquery validate / nobi-->

<script type="text/javascript">
    $(document).ready(function() {
        $("#user-change").validate({
            rules: {
                img:{
                	extension: 'jpg',
                },
            },
        });
        $("#user-change-pass").validate({
            rules: {
                oldPassword:{
                    minlength: 8
                },
                newPassword:{
                    minlength: 8
                },
                confirmPassword:{
                    minlength: 8,
                    equalTo: '#newpassword',
                },
            },
            
        });
    });
    $('.show-pass').click(function(){
        var input = $(this).parent()[0].querySelector('input');
        var icon = this.querySelector('i');
        if($(input).attr('type') == 'password') {
            input.setAttribute('type', 'text');
            icon.className = 'fa fa-eye';
        }
        else {
            input.setAttribute('type', 'password');
            icon.className = 'fa fa-eye-slash';
        } 
    })

</script>