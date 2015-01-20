{{-- Super User Navigation Bar Dashboard Active--}}
<div class="sidebar_navigation">
    <ul>
        <li title="Dashboard">
            <a href="{{ Route('showDashboard') }}">
                <span class="tab_label"><i class="glyphicon glyphicon-home"></i>&nbsp;&nbsp;&nbsp;Dashboard</span>
            </a>
        </li>

        <li title="Students">
            <a href="{{ Route('showStudentHome') }}">
                <span class="tab_label"><i class="fa fa-users"></i>&nbsp;&nbsp;&nbsp;Students</span>
            </a>
        </li>

        <li title="Applications">
            <a href="{{ Route('showApplications') }}">
                <span class="tab_label"><i class="glyphicon glyphicon-file"></i>&nbsp;&nbsp;&nbsp;Applications</span>
            </a>
        </li>

        <li title="Scholarships">
            <a href="{{ Route('showAllScholarships') }}">
                <span class="tab_label"><i class="glyphicon glyphicon-usd"></i>&nbsp;&nbsp;&nbsp;Scholarships</span>
            </a>
        </li>

        <li title="Awards" class="active">
            <a href="{{ Route('showAllAwards') }}">
                <span class="tab_label"><i class="glyphicon glyphicon-gift"></i>&nbsp;&nbsp;&nbsp;Awards</span>
            </a>
        </li>

        <li title="Reports">
            <a href="{{ Route('showReportsHome') }}">
                <span class="tab_label"><i class="glyphicon glyphicon-stats"></i>&nbsp;&nbsp;&nbsp;Reports</span>
            </a>
        </li>

        <li title="Notifications">
            <a href="{{ Route('homeNotifications') }}">
                <span class="tab_label"><i
                        class="glyphicon glyphicon-envelope"></i>&nbsp;&nbsp;&nbsp;Notifications</span>
            </a>
        </li>

        <li title="Processes">
            <a href="{{ Route('showProcesses') }}">
                <span class="tab_label"><i class="glyphicon glyphicon-time"></i>&nbsp;&nbsp;&nbsp;Processes</span>
            </a>
        </li>

        <li title="Settings">
            <a href="settings">
                <span class="tab_label"><i class="fa fa-cogs"></i>&nbsp;&nbsp;&nbsp;Settings</span>
            </a>
        </li>
    </ul>
    <img src="{{asset('images/Global/sunyOrangeBlock.png')}}" id="banner" width="125">
</div>
{{-- Super Navigation Bar End --}}

