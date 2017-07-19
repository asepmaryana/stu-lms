<header id="topnav" class="navbar navbar-midnightblue navbar-fixed-top clearfix" role="banner" ng-controller="TopMenuController">

	<span id="trigger-sidebar" class="toolbar-trigger toolbar-icon-bg">
		<a data-toggle="tooltips" data-placement="right" title="Toggle Sidebar"><span class="icon-bg"><i class="fa fa-fw fa-bars"></i></span></a>
	</span>

    <a class="navbar-brand" href="<?php echo site_url('home')?>">Sinergi</a>

    <span id="trigger-infobar" class="toolbar-trigger toolbar-icon-bg hide">
		<a data-toggle="tooltips" data-placement="left" title="Toggle Infobar"><span class="icon-bg"><i class="fa fa-fw fa-bell"></i></span></a>
    </span>


    <div class="yamm navbar-left navbar-collapse collapse in">
        <ul class="nav navbar-nav">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-home"></i> Home<span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#/map"><i class="fa fa-map-marker"></i> Map</a></li>
                    <li><a href="#/summaries"><i class="fa fa-dashboard"></i> Summaries</a></li>
                    <!-- <li class="divider"></li> -->
                </ul>
            </li>
            <li><a href="#/surveillance"><i class="fa fa-eye"></i> Surveillance</a></li>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart-o"></i> Reporting<span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#/datalog"><i class="fa fa-table"></i> Data log</a></li>
                    <li><a href="#/alarmlog"><i class="fa fa-bolt"></i> Alarm log</a></li>
                    <!-- <li class="divider"></li> -->
                </ul>
            </li>
            <?php if($this->session->userdata('roles_id') == '1'): ?>
            <li class="dropdown" id="widget-classicmenu">
                <a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-book"></i> Management<span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#/admin/customer"><i class="fa fa-user-md"></i> Customer</a></li>
                    <li><a href="#/admin/severity"><i class="fa fa-bolt"></i> Alarm Severity</a></li>
                    <li><a href="#/admin/alarm"><i class="fa fa-table"></i> Alarm List</a></li>
                    <li><a href="#/admin/region"><i class="fa fa-globe"></i> Region</a></li>
                    <li><a href="#/admin/area"><i class="fa fa-sitemap"></i>  Area</a></li>
                    <li><a href="#/admin/site"><i class="fa fa-map-marker"></i> Site</a></li>
                    <li><a href="#/admin/user"><i class="fa fa-users"></i> User</a></li>
                    <!-- <li class="divider"></li> -->
                </ul>
            </li>
            <?php endif; ?>
        </ul>
    </div>

    <ul class="nav navbar-nav toolbar pull-right">
        <li class="dropdown toolbar-icon-bg">
            <a href="#" id="navbar-links-toggle" data-toggle="collapse" data-target="header>.navbar-collapse">
				<span class="icon-bg">
					<i class="fa fa-fw fa-ellipsis-h"></i>
				</span>
            </a>
        </li>

        <li class="dropdown toolbar-icon-bg demo-search-hidden hide">
            <a href="#" class="dropdown-toggle tooltips" data-toggle="dropdown"><span class="icon-bg"><i class="fa fa-fw fa-search"></i></span></a>

            <div class="dropdown-menu dropdown-alternate arrow search dropdown-menu-form">
                <div class="dd-header">
                    <span>Search</span>
                    <span><a>Advanced search</a></span>
                </div>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder=""/>

                    <span class="input-group-btn">

						<a class="btn btn-primary" href="#">Search</a>
					</span>
                </div>
            </div>
        </li>
        
        <!--
        <li class="toolbar-icon-bg demo-headerdrop-hidden">
            <a id="headerbardropdown"><span class="icon-bg"><i class="fa fa-fw fa-level-down"></i></span></i></a>
        </li>        
        <li class="toolbar-icon-bg hidden-xs hide" id="trigger-fullscreen">
            <a class="toggle-fullscreen"><span class="icon-bg"><i class="fa fa-fw fa-arrows-alt"></i></span></i></a>
        </li>
        -->
        
        <li class="dropdown toolbar-icon-bg">
            <a class="dropdown-toggle" data-toggle='dropdown'><span class="icon-bg"><i class="fa fa-fw fa-user"></i></span></a>
            <ul class="dropdown-menu userinfo arrow">
                <li><a href="#/profile"><span class="pull-left">Profile</span> </a></li>				
				<li><a href="#/setting"><span class="pull-left">Settings</span> <i class="pull-right fa fa-cog"></i></a></li>
                <li class="divider"></li>
                <li><a ng-click="openpwd(password)"><span class="pull-left">Change Password</span> <i class="pull-right fa fa-key"></i></a></li>
                <li><a ng-click="logout()"><span class="pull-left">Sign Out</span> <i class="pull-right fa fa-sign-out"></i></a></li>
            </ul>
        </li>

    </ul>

</header>