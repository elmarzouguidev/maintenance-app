<htmlpagefooter name="maintenanceapp-footer">
    <table width="100%" cellpadding="0" cellspacing="0">
        @if ($hasHeader && $company)
            <tr>
                <td align="center" style="font-size:11px;line-height:1.25;color:#333;">
                    {{ $company->name }}<br />
                    {{ $company->addresse }}
                    Tel : {{ $company->telephone }}
                    E-mail : {{ $company->email }}<br />
                    -R.C:{{ $company->rc }}
                    -PATENTE:{{ $company->patente }}
                    -I.F:{{ $company->if }}
                    -CNSS:{{ $company->cnss }}
                    -ICE:{{ $company->ice }}
                </td>
            </tr>
            <tr>
                <td height="12" bgcolor="#1572A1">&nbsp;</td>
            </tr>
        @endif
        <tr>
            <td align="right" style="font-size:9px;color:#333;">
                Page {PAGENO} / {nbpg}
            </td>
        </tr>
    </table>
</htmlpagefooter>
<sethtmlpagefooter name="maintenanceapp-footer" value="on" />
