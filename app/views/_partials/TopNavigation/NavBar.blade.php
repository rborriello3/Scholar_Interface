<div class="navbar navbar-default navbar-fixed-top" role="navigation"
     style="min-height: 15px; background-color: #005696; font-size: 1.2em;">

    <a id="navBarHome" href="{{route('showDashboard')}}" class="glyphicon glyphicon-home" alt="home"
       title="Dashboard"></a>

    <ul class="navigationLinks">
        <li>
            <font color="#f37321">{{Auth::user()->name}}<font>
        </li>

        <li>
            <a href="{{route('session.logout')}}" class="glyphicon glyphicon-off" alt="logout" title="Log Out"></a>
        </li>

        <li>
            <a href="{{route('session.logout')}}" class="glyphicon glyphicon-user" alt="profile" title="Profile"></a>
        </li>

        <li>
            <a href="{{route('session.logout')}}" class="glyphicon glyphicon-cog" alt="settings"
               title="User Settings"></a>
        </li>

        <li>
            <a href="{{route('session.logout')}}" class="glyphicon glyphicon-bell" alt="messages" title="Messages"><span
                    class="badge badge-info">#</span></a>
        </li>

        <li>
            <a href="{{route('session.logout')}}" class="glyphicon glyphicon-question-sign" alt="support"
               title="Support"></a>
        </li>

        @if(Session::get('multiRoles') == 1 && ! Session::has('roles'))
        <li>
            <a href="{{route('showRoleSelect')}}" class="glyphicon glyphicon-refresh" alt="roles"
               title="Change Role"></a>
        </li>

        @elseif(Session::get('multiRoles') == 1 && Session::has('roles'))
        <li>
            {{ Form::open(array('route' => 'doRoleSelect', 'method' => 'POST', 'accept-charset' => 'UTF-8')) }}
            {{ Form::select('roleSelect', Session::get('roles'), Session::get('role')) }}
            <button type="submit" id="roleSubmit" alt="changeRole" title="Change Role"><i
                    class="glyphicon glyphicon-share-alt"></i></button>
            {{ Form::close() }}
        </li>
        @endif

        <li>
            {{ Form::open(array('route' => 'doAidYearSelect', 'method' => 'POST', 'accept-charset' => 'UTF-8')) }}
                {{ Form::select('globalAidYear', Session::get('aidyears'), Session::get('currentAidyear'))}}
                <button type="submit" id="roleSubmit" alt="changeAidYear" title="Change Aid Year"><i
                    class="glyphicon glyphicon-share-alt"></i></button>
            {{ Form::close() }}
        </li>
    </ul>
</div>