{{-- Super User Navigation Bar Dashboard Active--}}
<div class="sidebar_navigation">
    <ul>
        <li title="Dashboard" class="active">
            <a href="{{ Route('showDashboard') }}">
                <span class="tab_label"><i class="glyphicon glyphicon-home"></i>&nbsp;&nbsp;&nbsp;Dashboard</span>
            </a>
        </li>

        <li title="Scoring">
            <a href="{{ Route('showCommitteeApps') }}">
                <span class="tab_label"><i class="glyphicon glyphicon-check"></i>&nbsp;&nbsp;&nbsp;Scoring</span>
            </a>
        </li>

        <li title="Reports">
            <a href="{{ Route('showDashboard') }}">
                <span class="tab_label"><i class="glyphicon glyphicon-stats"></i>&nbsp;&nbsp;&nbsp;Reports</span>
            </a>
        </li>
    </ul>
    <img src="{{asset('images/Global/sunyOrangeBlock.png')}}" id="banner" width="125">
</div>
{{-- Super Navigation Bar End --}}

