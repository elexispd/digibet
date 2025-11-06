@extends($theme.'layouts.user')
@section('title')
    @lang('Bet History')
@endsection

@push('style')
    <style>
        .bet-history-card {
            background: var(--bgDark);
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,0.08);
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.24);
            backdrop-filter: blur(8px);
        }

        .bet-history-header {
            background: linear-gradient(135deg, var(--primary) 0%, #ffde29 100%);
            padding: 1.5rem 2rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            position: relative;
            overflow: hidden;
        }

        .bet-history-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 120px;
            height: 120px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        .bet-history-header h2 {
            margin: 0;
            font-weight: 800;
            font-size: 1.75rem;
            color: var(--black);
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
            z-index: 2;
        }

        .bet-history-header .badge {
            background: var(--black);
            color: var(--primary);
            font-weight: 700;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            position: relative;
            z-index: 2;
        }

        .bet-table {
            width: 100%;
            color: var(--white);
            border-collapse: separate;
            border-spacing: 0;
        }

        .bet-table thead {
            background: linear-gradient(180deg, rgba(35,34,38,0.95) 0%, rgba(35,34,38,0.85) 100%);
            backdrop-filter: blur(10px);
        }

        .bet-table thead th {
            padding: 1.25rem 1rem;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.8px;
            color: var(--white);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            position: relative;
        }

        .bet-table thead th:not(:last-child)::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 1px;
            height: 20px;
            background: rgba(255,255,255,0.1);
        }

        .bet-table tbody tr {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-bottom: 1px solid rgba(255,255,255,0.05);
            position: relative;
        }

        .bet-table tbody tr::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background: linear-gradient(90deg, rgba(253,214,15,0.1) 0%, transparent 100%);
            transition: width 0.3s ease;
        }

        .bet-table tbody tr:hover::before {
            width: 100%;
        }

        .bet-table tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        .bet-table tbody td {
            padding: 1.5rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            position: relative;
            z-index: 1;
        }

        .bet-status {
            padding: 0.6rem 1rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            display: inline-block;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }

        .bet-status:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }

        .status-processing {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: var(--black);
        }

        .status-win {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            color: var(--white);
        }

        .status-loss {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: var(--white);
        }

        .status-refund {
            background: linear-gradient(135deg, #6c757d 0%, #545b62 100%);
            color: var(--white);
        }

        .info-btn {
            background: rgba(253, 214, 15, 0.15);
            border: 1px solid rgba(253, 214, 15, 0.3);
            color: var(--primary);
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .info-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .info-btn:hover::before {
            left: 100%;
        }

        .info-btn:hover {
            background: rgba(253, 214, 15, 0.25);
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 4px 15px rgba(253, 214, 15, 0.3);
        }

        .amount-positive {
            color: #28a745;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
        }

        .amount-negative {
            color: #dc3545;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
        }

        .amount-neutral {
            color: var(--white);
            font-weight: 600;
        }

        .ratio-badge {
            background: rgba(255,255,255,0.1);
            color: var(--white);
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.85rem;
            border: 1px solid rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
        }

        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
            color: var(--gray);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            color: var(--gray);
            opacity: 0.7;
        }

        .empty-state h4 {
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--white);
        }

        .pagination-container {
            padding: 1.5rem 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            background: rgba(35,34,38,0.5);
        }

        /* Modal Styles */
        .bet-modal {
            background: var(--bgDark);
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.1);
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        }

        .bet-modal-header {
            background: linear-gradient(135deg, var(--primary) 0%, #ffde29 100%);
            padding: 1.5rem 2rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            position: relative;
            overflow: hidden;
        }

        .bet-modal-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        .bet-modal-header h5 {
            margin: 0;
            font-weight: 800;
            color: var(--black);
            position: relative;
            z-index: 2;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .bet-modal-body {
            padding: 2rem;
        }

        .bet-modal-footer {
            padding: 1.5rem 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            background: rgba(35,34,38,0.5);
        }

        .close-btn {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: var(--white);
            padding: 0.75rem 2rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }

        .close-btn:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        /* Modern scrollbar */
        .table-responsive::-webkit-scrollbar {
            height: 8px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 4px;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .bet-history-header {
                padding: 1.25rem 1.5rem;
            }
            
            .bet-history-header h2 {
                font-size: 1.5rem;
            }
            
            .bet-table thead {
                display: none;
            }
            
            .bet-table tbody tr {
                display: block;
                margin-bottom: 1.5rem;
                border: 1px solid rgba(255,255,255,0.1);
                border-radius: 12px;
                padding: 1.5rem;
                background: rgba(35,34,38,0.7);
                backdrop-filter: blur(10px);
            }
            
            .bet-table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 1rem 0;
                border-bottom: 1px solid rgba(255,255,255,0.05);
            }
            
            .bet-table tbody td:before {
                content: attr(data-label);
                font-weight: 700;
                color: var(--primary);
                text-transform: uppercase;
                font-size: 0.75rem;
                letter-spacing: 0.5px;
            }
            
            .bet-table tbody td:last-child {
                border-bottom: none;
            }
            
            .bet-modal-body {
                padding: 1.5rem;
            }
        }

        /* Animation for table rows */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .bet-table tbody tr {
            animation: fadeInUp 0.5s ease forwards;
        }

        .bet-table tbody tr:nth-child(1) { animation-delay: 0.05s; }
        .bet-table tbody tr:nth-child(2) { animation-delay: 0.1s; }
        .bet-table tbody tr:nth-child(3) { animation-delay: 0.15s; }
        .bet-table tbody tr:nth-child(4) { animation-delay: 0.2s; }
        .bet-table tbody tr:nth-child(5) { animation-delay: 0.25s; }
    </style>
@endpush

@section('content')
    <div class="row mt-4">
        <div class="col-12">
            <div class="bet-history-card">
                <div class="bet-history-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2><i class="fas fa-chart-line me-2"></i>@lang('Bet History')</h2>
                        <span class="badge">{{ $betInvests->total() }} @lang('Bets')</span>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table bet-table m-0">
                        <thead>
                            <tr>
                                <th></th>
                                <th>@lang('SL No.')</th>
                                <th>@lang('Invest Amount')</th>
                                <th>@lang('Return Amount')</th>
                                <th>@lang('Charge')</th>
                                <th>@lang('Ratio')</th>
                                <th class="text-center">@lang('Status')</th>
                                <th class="text-center">@lang('Information')</th>
                                <th>@lang('Time')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($betInvests as $key => $item)
                                <tr>
                                    <td data-label="@lang('SL No.')">
                                        <span class="amount-neutral">{{++$key}}</span>
                                    </td>
                                    <td data-label="@lang('Invest Amount')">
                                        <span class="amount-negative">{{currencyPosition($item->invest_amount)}}</span>
                                    </td>
                                    <td data-label="@lang('Return Amount')">
                                        <span class="amount-positive">{{currencyPosition($item->return_amount)}}</span>
                                    </td>
                                    <td data-label="@lang('Charge')">
                                        <span class="amount-neutral">{{currencyPosition($item->charge)}}</span>
                                    </td>
                                    <td data-label="@lang('Ratio')">
                                        <span class="ratio-badge">@lang($item->ratio)</span>
                                    </td>
                                    <td data-label="@lang('Status')" class="text-center">
                                        @if($item->status == 0)
                                            <span class="bet-status status-processing">@lang('Processing')</span>
                                        @elseif($item->status == 1)
                                            <span class="bet-status status-win">@lang('Win')</span>
                                        @elseif($item->status == -1)
                                            <span class="bet-status status-loss">@lang('Loss')</span>
                                        @elseif($item->status == 2)
                                            <span class="bet-status status-refund">@lang('Refund')</span>
                                        @endif
                                    </td>
                                    <td data-label="@lang('Information')" class="text-center">
                                        <button type="button" data-resource="{{$item->betInvestLog}}"
                                                data-bs-target="#investLogList" data-bs-toggle="modal"
                                                class="btn info-btn investLogList">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </td>
                                    <td data-label="@lang('Time')">
                                        <small class="text-muted">{{ dateTime($item->created_at, 'd M Y h:i A') }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="empty-state">
                                            <i class="fas fa-clipboard-list"></i>
                                            <h4>@lang('No Bets Found')</h4>
                                            <p>@lang('You haven\'t placed any bets yet. Start betting to see your history here!')</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($betInvests->hasPages())
                    <div class="pagination-container">
                        {{ $betInvests->appends($_GET)->links($theme.'partials.pagination') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bet Details Modal -->
    <div class="modal fade" id="investLogList" tabindex="-1" role="dialog" aria-labelledby="investLogListLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content bet-modal">
                <div class="modal-header bet-modal-header">
                    <h5 class="modal-title" id="investLogListLabel">
                        <i class="fas fa-info-circle me-2"></i>@lang('Bet Details')
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bet-modal-body">
                    <div class="table-responsive">
                        <table class="table bet-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>@lang('#')</th>
                                    <th>@lang('Match Name')</th>
                                    <th>@lang('Category Name')</th>
                                    <th>@lang('Tournament Name')</th>
                                    <th>@lang('Question Name')</th>
                                    <th>@lang('Option Name')</th>
                                    <th>@lang('Ratio')</th>
                                    <th>@lang('Result')</th>
                                </tr>
                            </thead>
                            <tbody class="result-body">
                                <!-- Content will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bet-modal-footer">
                    <button type="button" class="btn close-btn" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> @lang('Close')
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        'use strict'
        $(document).on('click', '.investLogList', function () {
            var obj = $(this).data('resource');
            var output = [];
            if (0 < obj.length) {
                obj.map(function (obj, i) {
                    var statusBadge = '';
                    if (obj.status == '0') {
                        statusBadge = `<span class='bet-status status-processing'>@lang('Processing')</span>`;
                    } else if (obj.status == '2') {
                        statusBadge = `<span class='bet-status status-win'>@lang('Win')</span>`;
                    } else if (obj.status == '-2') {
                        statusBadge = `<span class='bet-status status-loss'>@lang('Lose')</span>`;
                    } else if (obj.status == '3') {
                        statusBadge = `<span class='bet-status status-refund'>@lang('Refunded')</span>`;
                    }

                    var tr =
                        `<tr>
                            <td data-label="@lang('#')">${++i}</td>
                            <td data-label="@lang('Match Name')">${obj.match_name}</td>
                            <td data-label="@lang('Category Name')">${obj.category_icon} ${obj.category_name}</td>
                            <td data-label="@lang('Tournament Name')">${obj.tournament_name}</td>
                            <td data-label="@lang('Question Name')">${obj.question_name}</td>
                            <td data-label="@lang('Option Name')">${obj.option_name}</td>
                            <td data-label="@lang('Ratio')"><span class="ratio-badge">${obj.ratio}</span></td>
                            <td data-label="@lang('Result')">${statusBadge}</td>
                        </tr>`;

                    output[i] = tr;
                });

            } else {
                output[0] = `
                        <tr>
                            <td colspan="8">
                                <div class="empty-state py-4">
                                    <i class="fas fa-info-circle"></i>
                                    <p>@lang('No bet details available')</p>
                                </div>
                            </td>
                        </tr>`;
            }

            $('.result-body').html(output);
        });
    </script>
@endpush