<div id="sidebar" class="sidebar py-3">
    <div class="text-gray-400 text-uppercase px-3 px-lg-4 py-4 font-weight-bold small headings-font-family">MAIN</div>
    <ul class="sidebar-menu list-unstyled">
        <li class="sidebar-list-item mb-2">
            <a href="{{ url('district/home') }}" class="sidebar-link text-muted">
                <i class="fa fa-home mr-3 text-gray"></i><span>Home</span>
            </a>
        </li>
        <li class="sidebar-list-item mb-2">
            <a href="{{ url('district/manage/municipal/heads') }}" class="sidebar-link text-muted">
                <i class="fa fa-users mr-3 text-gray"></i><span>Municipal Heads</span>
            </a>
        </li>
        <li class="sidebar-list-item mb-2">
            <a href="{{ url('district/manage/citizens') }}" class="sidebar-link text-muted">
                <i class="fa fa-users mr-3 text-gray"></i><span>Citizens</span>
            </a>
        </li>
        <li class="sidebar-list-item mb-2">
            <a href="{{ url('district/agency') }}" class="sidebar-link text-muted">
                <i class="fa fa-building mr-3 text-gray"></i><span>Agency</span>
            </a>
        </li>
        <li class="sidebar-list-item mb-2">
            <a href="{{ url('district/reports') }}" class="sidebar-link text-muted">
                <i class="fa fa-file mr-3 text-gray"></i><span>Reports</span>
            </a>
        </li>
    </ul>
</div>