<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <div class="sb-sidenav-menu-heading">Core</div>
            <a class="nav-link" href="{{url('dashboard')}}">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-house"></i></div>
                Dashboard
            </a>

            <a class="nav-link" href="{{url('teacher')}}">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-chalkboard-user"></i></div>
                Teacher
            </a>

            <a class="nav-link" href="{{url('student')}}">
                <div class="sb-nav-link-icon"><i class="fas fa-user-graduate"></i></div>
                Student
            </a>
            <a class="nav-link" href="{{url('major')}}">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-book-open-reader"></i></div>
                Major
            </a>

            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseManageScore" aria-expanded="false" aria-controls="collapseManageClass">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                Manage Score
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseManageScore" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="{{url('grade')}}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-trophy"></i></div>
                        Grade
                    </a>
                    <a class="nav-link" href="{{url('term')}}">
                        <div class="sb-nav-link-icon"><i class="fa-regular fa-calendar-check"></i></div>
                        Term
                    </a>
                    <a class="nav-link" href="{{url('generation')}}">
                        <div class="sb-nav-link-icon"><i class="fa-regular fa-calendar-check"></i></div>
                        Generation
                    </a>
                </nav>
            </div>
           

            <div class="sb-sidenav-menu-heading">Interface</div>

            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseManageClass" aria-expanded="false" aria-controls="collapseManageClass">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-book"></i></div>
                Manage Class
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseManageClass" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="{{url('subject')}}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-book-open"></i></div>
                        Subject
                    </a>
                    <a class="nav-link" href="{{url('class')}}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-book-bookmark"></i></div>
                        Class
                    </a>
                </nav>
            </div>
           
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseManageStaff" aria-expanded="false" aria-controls="collapseManageStaff">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-clipboard-user"></i></div>
                Manage Employee
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseManageStaff" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="{{url('employee')}}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
                        Employee List
                    </a>
                    <a class="nav-link" href="{{url('position')}}">
                        <div class="sb-nav-link-icon"><i class="fa-regular fa-clock"></i></div>
                        Positions
                    </a>
                </nav>
            </div>


            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseManageUser" aria-expanded="false" aria-controls="collapseManageStaff">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-clipboard-user"></i></div>
                Manage User
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseManageUser" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="{{url('user')}}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
                        User List
                    </a>
                    <a class="nav-link" href="{{url('role')}}">
                        <div class="sb-nav-link-icon"><i class="fa-regular fa-clock"></i></div>
                        Role List
                    </a>
                </nav>
            </div>

            <div class="sb-sidenav-menu-heading">Addons</div>
            <a class="nav-link" href="charts.html">
                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                Charts
            </a>
            <a class="nav-link" href="tables.html">
                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                Tables
            </a>
        </div>
    </div>
    {{-- <div class="sb-sidenav-footer">
        <div class="small">Logged in as:</div>
        Start Bootstrap
    </div> --}}
</nav>