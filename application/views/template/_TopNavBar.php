<header id="topnav" class="navbar navbar-midnightblue navbar-fixed-top clearfix" role="banner">

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
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-home"></i> Home<span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="<?php echo site_url('dashboard')?>"><i class="fa fa-map-marker"></i> Map</a></li>
                    <li><a href="#"><i class="fa fa-dashboard"></i> Summaries</a></li>
                    <!-- <li class="divider"></li> -->
                </ul>
            </li>
            <li><a href="<?php echo site_url('monitoring')?>"><i class="fa fa-eye"></i> Surveillance</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa-files-o"></i> Reporting<span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#"><i class="fa fa-map-marker"></i> Map</a></li>
                    <li><a href="#"><i class="fa fa-dashboard"></i> Summaries</a></li>
                    <!-- <li class="divider"></li> -->
                </ul>
            </li>
            
            
            <?php if($this->session->userdata('roles_id') == '1'): ?>
            <li class="dropdown" id="widget-classicmenu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-book"></i> Management<span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="<?php echo site_url('customer')?>">Customer</a></li>
                    <li><a href="<?php echo site_url('users')?>">Account</a></li>
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
                    <span><a href="#">Advanced search</a></span>
                </div>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder=""/>

                    <span class="input-group-btn">

						<a class="btn btn-primary" href="#">Search</a>
					</span>
                </div>
            </div>
        </li>

        <li class="toolbar-icon-bg demo-headerdrop-hidden">
            <a href="#" id="headerbardropdown"><span class="icon-bg"><i class="fa fa-fw fa-level-down"></i></span></i></a>
        </li>

        <li class="toolbar-icon-bg hidden-xs hide" id="trigger-fullscreen">
            <a href="#" class="toggle-fullscreen"><span class="icon-bg"><i class="fa fa-fw fa-arrows-alt"></i></span></i></a>
        </li>

        <li class="dropdown toolbar-icon-bg">
            <a href="#" class="dropdown-toggle" data-toggle='dropdown'><span class="icon-bg"><i class="fa fa-fw fa-user"></i></span></a>
            <ul class="dropdown-menu userinfo arrow">
                <li class="divider"></li>
                <li><a href="#" onclick="logout()"><span class="pull-left">Sign Out</span> <i class="pull-right fa fa-sign-out"></i></a></li>
            </ul>
        </li>

    </ul>

</header>