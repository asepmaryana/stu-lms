<div class="static-sidebar-wrapper sidebar-midnightblue" ng-controller="LeftMenuController">
    <div class="static-sidebar">
        <div class="sidebar">
            <!--
            <div class="widget stay-on-collapse" id="widget-welcomebox ">
                <div class="widget-body welcome-box tabular">
                    <h5>09:00, WED 01-9-2016 </h5>
                    <div class="tabular-row hidden ">
                        <div class="tabular-cell welcome-avatar">
                            <a href="#"><img src="<?php echo base_url(); ?>assets/demo/avatar/avatar_02.png" class="avatar"/></a>
                        </div>
                        <div class="tabular-cell welcome-options">
                            <span class="welcome-text">Welcome,</span>
                            <a href="#" class="name">Jonathan Smith</a>
                        </div>
                    </div>
                </div>
            </div>
            -->
            <div class="widget stay-on-collapse" id="widget-sidebar">
                <nav role="navigation" class="widget-body">
                    <ul class="acc-menu">
                        <li class="nav-separator">Network Tree</li>
                        <li><a href="<?php echo base_url(); ?>"><i class="fa fa-refresh"></i><span>Refresh</span></a></li>
                        <?php foreach($regions as $r): ?>
                        <li>
                            <a href="javascript:;">
                                <i class="fa fa-globe"></i><span> <?php echo $r->name; ?></span><span class="badge badge-primary"><?php echo count($r->children); ?></span>
                            </a>
                            <?php if(count($r->children) > 0): ?>      
                			<ul class="acc-menu">
                                <?php foreach($r->children as $a): ?>
                				<li>
                                    <a href="javascript:;">
                                        <i class="fa fa-sitemap"></i><span> <?php echo $a->name; ?></span><span class="badge badge-primary"><?php echo count($a->children); ?></span>
                                    </a>
                                    <?php if(count($a->children) > 0): ?>
                                    <ul class="acc-menu">
                                    <?php foreach($a->children as $s): ?>
                                        <li><a href="javascript:;" ng-click="open('<?php echo $s->id; ?>')"><i class="fa fa-map-marker"></i><span> <?php echo $s->name; ?></span> <?php if($s->consent_id == '' || $s->consent_id == null) print ' <span class="label label-alizarin">Master</span>'; ?></a></li>
                                    <?php endforeach; ?>
                                    </ul>
                                    <?php endif; ?>
                                </li>
                                <?php endforeach; ?>
                			</ul>
                            <?php endif; ?>
                		</li>
                        <?php endforeach; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>