<aside class="w-64 bg-gradient-to-b from-surface-900 via-surface-900 to-surface-800 min-h-screen flex flex-col shadow-2xl shrink-0">
    <div class="p-6 border-b border-surface-700/50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/20">
                <i class="fa-solid fa-users text-white text-lg"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold text-white tracking-tight">HR<span class="text-primary-400">.</span></h1>
                <p class="text-[10px] text-surface-400 uppercase tracking-widest font-medium">Management System</p>
            </div>
        </div>
    </div>

    <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
        <p class="text-[10px] text-surface-500 uppercase tracking-widest font-semibold px-3 mb-2">Main Menu</p>

        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-primary-500/10 text-primary-300 shadow-sm' : 'text-surface-300 hover:bg-surface-800 hover:text-white' }}">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('dashboard') ? 'bg-primary-500/20 text-primary-300' : 'bg-surface-800 text-surface-400 group-hover:bg-surface-700' }} transition-all duration-200">
                <i class="fa-solid fa-chart-pie text-sm"></i>
            </span>
            <span>Dashboard</span>
            @if(request()->routeIs('dashboard'))
                <span class="ml-auto w-1.5 h-1.5 rounded-full bg-primary-400"></span>
            @endif
        </a>

        <a href="{{ route('modules') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('modules', 'employees.*') ? 'bg-primary-500/10 text-primary-300 shadow-sm' : 'text-surface-300 hover:bg-surface-800 hover:text-white' }}">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('modules', 'employees.*') ? 'bg-primary-500/20 text-primary-300' : 'bg-surface-800 text-surface-400 group-hover:bg-surface-700' }} transition-all duration-200">
                <i class="fa-solid fa-user-tie text-sm"></i>
            </span>
            <span>Employees</span>
            @if(request()->routeIs('modules', 'employees.*'))
                <span class="ml-auto w-1.5 h-1.5 rounded-full bg-primary-400"></span>
            @endif
        </a>

        @if(Auth::user()->hasPermission('manage-companies'))
        <a href="{{ route('companies.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('companies.*') ? 'bg-primary-500/10 text-primary-300 shadow-sm' : 'text-surface-300 hover:bg-surface-800 hover:text-white' }}">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('companies.*') ? 'bg-primary-500/20 text-primary-300' : 'bg-surface-800 text-surface-400 group-hover:bg-surface-700' }} transition-all duration-200">
                <i class="fa-solid fa-city text-sm"></i>
            </span>
            <span>Companies</span>
            @if(request()->routeIs('companies.*'))
                <span class="ml-auto w-1.5 h-1.5 rounded-full bg-primary-400"></span>
            @endif
        </a>
        @endif

        @if(Auth::user()->hasPermission('manage-settings'))
        <a href="{{ route('departments.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('departments.*') ? 'bg-primary-500/10 text-primary-300 shadow-sm' : 'text-surface-300 hover:bg-surface-800 hover:text-white' }}">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('departments.*') ? 'bg-primary-500/20 text-primary-300' : 'bg-surface-800 text-surface-400 group-hover:bg-surface-700' }} transition-all duration-200">
                <i class="fa-solid fa-building text-sm"></i>
            </span>
            <span>Departments</span>
            @if(request()->routeIs('departments.*'))
                <span class="ml-auto w-1.5 h-1.5 rounded-full bg-primary-400"></span>
            @endif
        </a>

        <a href="{{ route('positions.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('positions.*') ? 'bg-primary-500/10 text-primary-300 shadow-sm' : 'text-surface-300 hover:bg-surface-800 hover:text-white' }}">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('positions.*') ? 'bg-primary-500/20 text-primary-300' : 'bg-surface-800 text-surface-400 group-hover:bg-surface-700' }} transition-all duration-200">
                <i class="fa-solid fa-briefcase text-sm"></i>
            </span>
            <span>Positions</span>
            @if(request()->routeIs('positions.*'))
                <span class="ml-auto w-1.5 h-1.5 rounded-full bg-primary-400"></span>
            @endif
        </a>
        @endif

        <p class="text-[10px] text-surface-500 uppercase tracking-widest font-semibold px-3 mb-2 mt-6">Operations</p>

        @if(Auth::user()->hasPermission('view-attendance') || Auth::user()->hasPermission('manage-attendance'))
        <a href="{{ route('attendance.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('attendance.*') ? 'bg-primary-500/10 text-primary-300 shadow-sm' : 'text-surface-300 hover:bg-surface-800 hover:text-white' }}">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('attendance.*') ? 'bg-primary-500/20 text-primary-300' : 'bg-surface-800 text-surface-400 group-hover:bg-surface-700' }} transition-all duration-200">
                <i class="fa-solid fa-clock text-sm"></i>
            </span>
            <span>Attendance</span>
            @if(request()->routeIs('attendance.*'))
                <span class="ml-auto w-1.5 h-1.5 rounded-full bg-primary-400"></span>
            @endif
        </a>
        @endif

        @if(Auth::user()->hasPermission('view-leaves') || Auth::user()->hasPermission('approve-leave'))
        <a href="{{ route('leaves.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('leaves.*') ? 'bg-primary-500/10 text-primary-300 shadow-sm' : 'text-surface-300 hover:bg-surface-800 hover:text-white' }}">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('leaves.*') ? 'bg-primary-500/20 text-primary-300' : 'bg-surface-800 text-surface-400 group-hover:bg-surface-700' }} transition-all duration-200">
                <i class="fa-solid fa-calendar-days text-sm"></i>
            </span>
            <span>Leave</span>
            @if(request()->routeIs('leaves.*'))
                <span class="ml-auto w-1.5 h-1.5 rounded-full bg-primary-400"></span>
            @endif
        </a>
        @endif

        @if(Auth::user()->hasPermission('view-payroll') || Auth::user()->hasPermission('manage-payroll'))
        <a href="{{ route('payrolls.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('payrolls.*') ? 'bg-primary-500/10 text-primary-300 shadow-sm' : 'text-surface-300 hover:bg-surface-800 hover:text-white' }}">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('payrolls.*') ? 'bg-primary-500/20 text-primary-300' : 'bg-surface-800 text-surface-400 group-hover:bg-surface-700' }} transition-all duration-200">
                <i class="fa-solid fa-coins text-sm"></i>
            </span>
            <span>Payroll</span>
            @if(request()->routeIs('payrolls.*'))
                <span class="ml-auto w-1.5 h-1.5 rounded-full bg-primary-400"></span>
            @endif
        </a>
        @endif

        @if(Auth::user()->hasPermission('view-evaluations') || Auth::user()->hasPermission('manage-evaluations'))
        <a href="{{ route('evaluations.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('evaluations.*') ? 'bg-primary-500/10 text-primary-300 shadow-sm' : 'text-surface-300 hover:bg-surface-800 hover:text-white' }}">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('evaluations.*') ? 'bg-primary-500/20 text-primary-300' : 'bg-surface-800 text-surface-400 group-hover:bg-surface-700' }} transition-all duration-200">
                <i class="fa-solid fa-star text-sm"></i>
            </span>
            <span>Performance</span>
            @if(request()->routeIs('evaluations.*'))
                <span class="ml-auto w-1.5 h-1.5 rounded-full bg-primary-400"></span>
            @endif
        </a>
        @endif

        @if(Auth::user()->hasPermission('view-documents') || Auth::user()->hasPermission('manage-documents'))
        <a href="{{ route('documents.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('documents.*') ? 'bg-primary-500/10 text-primary-300 shadow-sm' : 'text-surface-300 hover:bg-surface-800 hover:text-white' }}">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('documents.*') ? 'bg-primary-500/20 text-primary-300' : 'bg-surface-800 text-surface-400 group-hover:bg-surface-700' }} transition-all duration-200">
                <i class="fa-solid fa-folder-open text-sm"></i>
            </span>
            <span>Documents</span>
            @if(request()->routeIs('documents.*'))
                <span class="ml-auto w-1.5 h-1.5 rounded-full bg-primary-400"></span>
            @endif
        </a>
        @endif

        @if(Auth::user()->hasPermission('manage-users'))
        <a href="{{ route('users.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('users.*') ? 'bg-primary-500/10 text-primary-300 shadow-sm' : 'text-surface-300 hover:bg-surface-800 hover:text-white' }}">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('users.*') ? 'bg-primary-500/20 text-primary-300' : 'bg-surface-800 text-surface-400 group-hover:bg-surface-700' }} transition-all duration-200">
                <i class="fa-solid fa-user-shield text-sm"></i>
            </span>
            <span>Users &amp; Access</span>
            @if(request()->routeIs('users.*'))
                <span class="ml-auto w-1.5 h-1.5 rounded-full bg-primary-400"></span>
            @endif
        </a>
        @endif

        @if(Auth::user()->hasPermission('view-warnings') || Auth::user()->hasPermission('manage-warnings'))
        <a href="{{ route('warning-letters.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('warning-letters.*') ? 'bg-primary-500/10 text-primary-300 shadow-sm' : 'text-surface-300 hover:bg-surface-800 hover:text-white' }}">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('warning-letters.*') ? 'bg-primary-500/20 text-primary-300' : 'bg-surface-800 text-surface-400 group-hover:bg-surface-700' }} transition-all duration-200">
                <i class="fa-solid fa-triangle-exclamation text-sm"></i>
            </span>
            <span>Warnings</span>
            @if(request()->routeIs('warning-letters.*'))
                <span class="ml-auto w-1.5 h-1.5 rounded-full bg-primary-400"></span>
            @endif
        </a>
        @endif

        @if(Auth::user()->hasPermission('view-reports'))
        <p class="text-[10px] text-surface-500 uppercase tracking-widest font-semibold px-3 mb-2 mt-6">Other</p>

        <a href="{{ route('reports.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('reports.*') ? 'bg-primary-500/10 text-primary-300 shadow-sm' : 'text-surface-300 hover:bg-surface-800 hover:text-white' }}">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('reports.*') ? 'bg-primary-500/20 text-primary-300' : 'bg-surface-800 text-surface-400 group-hover:bg-surface-700' }} transition-all duration-200">
                <i class="fa-solid fa-chart-simple text-sm"></i>
            </span>
            <span>Reports</span>
            @if(request()->routeIs('reports.*'))
                <span class="ml-auto w-1.5 h-1.5 rounded-full bg-primary-400"></span>
            @endif
        </a>
        @endif

        <a href="{{ route('notifications.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('notifications.*') ? 'bg-primary-500/10 text-primary-300 shadow-sm' : 'text-surface-300 hover:bg-surface-800 hover:text-white' }}">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('notifications.*') ? 'bg-primary-500/20 text-primary-300' : 'bg-surface-800 text-surface-400 group-hover:bg-surface-700' }} transition-all duration-200">
                <i class="fa-solid fa-bell text-sm"></i>
            </span>
            <span>Notifications</span>
            @if(request()->routeIs('notifications.*'))
                <span class="ml-auto w-1.5 h-1.5 rounded-full bg-primary-400"></span>
            @endif
        </a>

        @if(Auth::user()->hasPermission('view-logs'))
        <a href="{{ route('logs.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('logs.*') ? 'bg-primary-500/10 text-primary-300 shadow-sm' : 'text-surface-300 hover:bg-surface-800 hover:text-white' }}">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('logs.*') ? 'bg-primary-500/20 text-primary-300' : 'bg-surface-800 text-surface-400 group-hover:bg-surface-700' }} transition-all duration-200">
                <i class="fa-solid fa-clock-rotate-left text-sm"></i>
            </span>
            <span>Activity Logs</span>
            @if(request()->routeIs('logs.*'))
                <span class="ml-auto w-1.5 h-1.5 rounded-full bg-primary-400"></span>
            @endif
        </a>
        @endif

        @if(Auth::user()->hasPermission('manage-settings'))
        <a href="{{ route('settings.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('settings.*') ? 'bg-primary-500/10 text-primary-300 shadow-sm' : 'text-surface-300 hover:bg-surface-800 hover:text-white' }}">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('settings.*') ? 'bg-primary-500/20 text-primary-300' : 'bg-surface-800 text-surface-400 group-hover:bg-surface-700' }} transition-all duration-200">
                <i class="fa-solid fa-gear text-sm"></i>
            </span>
            <span>Settings</span>
            @if(request()->routeIs('settings.*'))
                <span class="ml-auto w-1.5 h-1.5 rounded-full bg-primary-400"></span>
            @endif
        </a>
        @endif
    </nav>

    <div class="p-4 border-t border-surface-700/50 bg-surface-800/50">
        <div class="flex items-center gap-3 px-2">
            <div class="w-9 h-9 bg-gradient-to-br from-accent-400 to-accent-600 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-lg shadow-accent-500/20 shrink-0">
                {{ substr(Auth::user()->name, 0, 2) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-surface-400 truncate">{{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>
</aside>
