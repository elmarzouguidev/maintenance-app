<li>
    <a href="javascript: void(0);" class="has-arrow waves-effect">
        <i class="bx bx-task"></i>
        <span key="t-tasks">Rapports</span>
    </a>
    <ul class="sub-menu" aria-expanded="false">
        @can('report.browse')
            <li>
                <a href="{{ route('admin:rapports.index') }}" key="t-rapports-list">
                    <i class="bx bx-task"></i>
                    List
                </a>
            </li>
        @endcan
        @can('report.edit')
            <li>
                <a href="{{ route('admin:rapports.editions.index') }}" key="t-rapports-list-edit">
                    <i class="bx bx-task"></i>
                    Edition
                </a>
            </li>
        @endcan
    </ul>
</li>
