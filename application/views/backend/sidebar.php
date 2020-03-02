<!-- Sidebar user panel -->
<div class="user-panel">
    <div class="pull-left image">
        <img src="<?php echo base_url().'assets/'; ?>dist/img/diesel.jpg" class="img-circle" alt="User Image">
    </div>
    <div class="pull-left info">
        <p><?php echo $this->session->userdata('first_name'); ?></p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
    </div>
</div>

<!-- search form
<form action="#" method="get" class="sidebar-form">
    <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search...">
        <span class="input-group-btn">
            <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
        </span>
    </div>
</form>
 /.search form -->

<!-- sidebar menu: : style can be found in sidebar.less -->
<ul class="sidebar-menu">
    <li class="header">MENU UTAMA</li>
    <li>
        <a href="<?php echo site_url('dashboard');?>">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-users"></i>
             <span>Users Management </span>
            <span class="label label-primary pull-right">2</span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?php echo site_url('users');?>"><i class="fa fa-circle-o"></i> Users</a></li>          
            <li><a href="<?php echo site_url('groups');?>"><i class="fa fa-circle-o"></i> Groups</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-users"></i>
             <span>Master Data</span>
            <span class="label label-primary pull-right">4</span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?php echo site_url('mahasiswa');?>"><i class="fa fa-circle-o"></i> Mahasiswa</a></li>          
            <li><a href="<?php echo site_url('dosen');?>"><i class="fa fa-circle-o"></i> Dosen</a></li>
            <li><a href="<?php echo site_url('prodi');?>"><i class="fa fa-circle-o"></i> Program Studi</a></li>
            <li><a href="<?php echo site_url('thnAkademik');?>"><i class="fa fa-circle-o"></i> Tahun Akademik</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-users"></i>
             <span>Kelas Management </span>
            <span class="label label-primary pull-right">3</span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?php echo site_url('kelas');?>"><i class="fa fa-circle-o"></i> Kelas</a></li>          
            <li><a href="<?php echo site_url('mhsKelas');?>"><i class="fa fa-circle-o"></i> Mahasiswa Kelas</a></li>
            <li><a href="<?php echo site_url('dsnKelas');?>"><i class="fa fa-circle-o"></i> Dosen Kelas</a></li>
        </ul>
    </li>

    <li class="treeview">
        <a href="#">
            <i class="fa fa-users"></i>
             <span>Kuisoner Management </span>
            <span class="label label-primary pull-right">4</span>
        </a>
        <ul class="treeview-menu">
            <li>
                <a href="<?php echo site_url('group');?>">
                    <i class="fa fa-circle-o"></i> <span>Group Pertanyaan</span>
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('groupdosen');?>">
                    <i class="fa fa-circle-o"></i> <span>Group Dosen</span>
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('pertanyaan');?>">
                    <i class="fa fa-circle-o"></i> <span>Pertanyaan</span>
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('pertanyaandosen');?>">
                    <i class="fa fa-circle-o"></i> <span>Pertanyaan Dosen</span>
                </a>
            </li>
        </ul>
    </li>
    
    <li>
        <a href="<?php echo site_url('hasildosen');?>">
            <i class="fa fa-users"></i> <span>Hasil Dosen</span>
        </a>
    </li>
    <li>
        <a href="<?php echo site_url('hasil');?>">
            <i class="fa fa-users"></i> <span>Hasil Kuisoner</span>
        </a>
    </li>
    <li class="header"></li>
    <li>
        <a href="<?php echo site_url('auth/logout');?>">
            <i class="fa fa-power-off"></i> <span>Logout</span>
        </a>
    </li>
  
</ul>