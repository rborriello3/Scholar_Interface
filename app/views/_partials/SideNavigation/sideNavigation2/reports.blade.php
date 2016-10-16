{{-- Super User Navigation Bar Reports Active--}}
<div class="sidebar_navigation">
    <ul>
        <li title="Dashboard">
            <a href="{{ Route('showDashboard') }}" class="i_dashboard">
                <span class="tab_label"><i class="glyphicon glyphicon-home"></i>&nbsp;&nbsp;&nbsp;Dashboard</span>
            </a>
        </li>

        <li title="Users" class="active">
            <a href="{{ Route('showUsers')}}">
                <span class="tab_label"><i class="fa fa-users"></i>&nbsp;&nbsp;&nbsp;Users</span>
            </a>
        </li>

        <li title="Reports" class="active">
            <a href="reports">
                <span class="tab_label"><i class="glyphicon glyphicon-stats"></i>&nbsp;&nbsp;&nbsp;Reports</span>
            </a>
        </li>

        <li title="Settings">
            <a href="{{ Route('showSettingsPage') }}">
                <span class="tab_label"><i class="fa fa-cogs"></i>&nbsp;&nbsp;&nbsp;Settings</span>
            </a>
        </li>
    </ul>
    <img src="{{asset('images/Global/sunyOrangeBlock.png')}}" id="banner" width="125">
</div>
{{-- Super Navigation Bar End --}}

