<div class="sidebar_navigation">
    <ul>
        <li title="Dashboard">
            <a href="{{ Route('showDashboard') }}">
                <span class="tab_label"><i class="glyphicon glyphicon-home"></i>&nbsp;&nbsp;&nbsp;Dashboard</span>
            </a>
        </li>

        <li title="Responses">
            <a href="{{ Route('showResponseHome') }}">
                <span class="tab_label"><i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;&nbsp;Responses</span>
            </a>
        </li>

        <li title="Students" class="active">
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

        <li title="Awards">
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
    </ul>
    <img src="{{asset('images/Global/sunyOrangeBlock.png')}}" id="banner" width="125">
</div>
{{-- Super Navigation Bar End --}}

