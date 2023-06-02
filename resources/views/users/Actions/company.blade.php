@if(app()->getLocale()=='en') {{ $row->company->company_name_en }} @else {{ $row->company->company_name_ar }} @endif
