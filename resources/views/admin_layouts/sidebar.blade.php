<div id="sidebar" class="sidebar py-3">
    <div class="text-gray-400 text-uppercase px-3 px-lg-4 py-4 font-weight-bold small headings-font-family">MAIN</div>
    <ul class="sidebar-menu list-unstyled">
        <li class="sidebar-list-item mb-2">
            <a href="{{ url('administrator/home') }}" class="sidebar-link text-muted">
                <i class="fa fa-home mr-3 text-gray"></i><span>Home</span>
            </a>
        </li>
        <li class="sidebar-list-item mb-2">
            <a href="{{ url('administrator/district') }}" class="sidebar-link text-muted">
                <i class="o-1 fa fa-location-arrow mr-3 text-gray"></i><span>District</span>
            </a>
        </li>
        <li class="sidebar-list-item mb-2">
            <a href="{{ url('administrator/manage/district/heads') }}" class="sidebar-link text-muted">
                <i class="fa fa-users mr-3 text-gray"></i><span>District Heads</span>
            </a>
        </li>
        <li class="sidebar-list-item mb-2">
            <a href="{{ url('administrator/municipal') }}" class="sidebar-link text-muted">
                <i class="fa fa-map-marker mr-3 text-gray"></i><span>Municipal</span>
            </a>
        </li>
        <li class="sidebar-list-item mb-2">
            <a href="{{ url('administrator/manage/municipal/heads') }}" class="sidebar-link text-muted">
                <i class="fa fa-users mr-3 text-gray"></i><span>Municipal Heads</span>
            </a>
        </li>
        <li class="sidebar-list-item mb-2">
            <a href="{{ url('administrator/manage/citizens') }}" class="sidebar-link text-muted">
                <i class="fa fa-users mr-3 text-gray"></i><span>Citizens</span>
            </a>
        </li>
        <li class="sidebar-list-item mb-2">
            <a href="{{ url('administrator/agency') }}" class="sidebar-link text-muted">
                <i class="fa fa-building mr-3 text-gray"></i><span>Agency</span>
            </a>
        </li>
        <li class="sidebar-list-item mb-2">
            <a href="{{ url('administrator/reports') }}" class="sidebar-link text-muted">
                <i class="fa fa-file mr-3 text-gray"></i><span>Reports</span>
            </a>
        </li>
    </ul>
</div>