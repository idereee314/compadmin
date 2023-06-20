        <!--begin::Entry-->
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container">
                <!--begin::Card-->
                <div class="card card-custom gutter-b">
                    <input type="hidden" name="tab_id" id="tab_id" value="{{ isset($tab_id)? $tab_id: 'tab1-1'}}"/>
                    <input type="hidden" name="event_id" id="event_id" value="{{ @$eventConfig->event->id }}"/>
                    <!--begin::Card header-->
                    <div class="card-header card-header-tabs-line nav-tabs-line-3x">
                        <!--begin::Toolbar-->
                        <div class="card-toolbar">
                            <ul class="nav nav-tabs nav-bold nav-tabs-line nav-tabs-line-3x">
                                @forelse(@$tabs as $tab)
                                <li class="nav-item mr-3 {{@$tab_id == $tab['number'] ? 'active' : '' }}">
                                    <a href="#{{$tab['number']}}" data-toggle="tab" name="{{$tab['number']}}" class="nav-link app_tab" data-tabid="{{$tab['number']}}"  data-tabcode="{{$tab['code']}}" data-tabname="{{$tab['name']}}">
                                        <span class="nav-icon">
                                            <i class="fas {{ @$tab['icon'] }}"></i>
                                        </span>
                                        <span class="nav-text font-size-lg">{{ $tab['title'] }}</span>
                                    </a>
                                </li>
                                @empty
                                @endforelse
                            </ul>
                        </div>
                        <div class="pull-right"></div>
                        <div class="clearfix"></div>
                    </div>
                    <!--end::Card header-->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in {{@$tab_id == $tab['number'] ? 'active show' : '' }}" id="tab1-1">
                                <!-- <h1 style="color: #0f4b63;"> Монгол жюү жицүгийн холбоо нас болон жингийн ангиллуудыг тодорхойлж, дараах байдлаар хуваадаг.</h1> -->
                                <h2 style="color: #0f4b63;">НАСНИЙ АНГИЛАЛ</h2>
                                
                                <div class="row">
                                    <div class="col">                            
                                        <div class="card">
                                            <div class="card-header" style="color: #0f4b63;"><strong>16 аас доош насныхан</strong></div>
                                            <div class="card-body" style="color: #0f4b63;">                                        
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered">                                               
                                                        <thead style="background-color: ">
                                                            <tr> 
                                                                <th class="text-center" style="color: #0f4b63;">Монгол нэр</th>                                                                                                                                     
                                                                <th class="text-center" style="color: #0f4b63;">Англи нэр</th>
                                                                <th class="text-center" style="color: #0f4b63;">Эхлэх нас</th>
                                                                <th class="text-center" style="color: #0f4b63;">Дуусах нас</th>
                                                                <th class="text-center" style="color: #0f4b63;">Эхлэх он</th>
                                                                <th class="text-center" style="color: #0f4b63;">Дуусах Он</th>                                                
                                                            </tr>
                                                        </thead>
                                                        <tbody>                                                    
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">Залуучууд</td>                                                     
                                                                <td class="text-center border-right" style="color: #0f4b63;">Youth</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">16</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">17</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">{{ date('Y', strtotime('-16 years')) }}</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">{{ date('Y', strtotime('-17 years')) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">Насанд хүрэгч</td>                                                     
                                                                <td class="text-center border-right" style="color: #0f4b63;">Adult</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">18</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">29</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">{{ date('Y', strtotime('-18 years')) }}</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">{{ date('Y', strtotime('-29 years')) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">Мастер 1</td>                                                     
                                                                <td class="text-center border-right" style="color: #0f4b63;">Master 1</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">30</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">35</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">{{ date('Y', strtotime('-30 years')) }}</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">{{ date('Y', strtotime('-35 years')) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">Мастер 2</td>                                                     
                                                                <td class="text-center border-right" style="color: #0f4b63;">Master 2</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">36</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">40</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">{{ date('Y', strtotime('-36 years')) }}</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">{{ date('Y', strtotime('-40 years')) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">Мастер 3</td>                                                     
                                                                <td class="text-center border-right" style="color: #0f4b63;">Master 3</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">41</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">45</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">{{ date('Y', strtotime('-41 years')) }}</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">{{ date('Y', strtotime('-45 years')) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">Мастер 4</td>                                                     
                                                                <td class="text-center border-right" style="color: #0f4b63;">Master 4</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">46</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">50</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">{{ date('Y', strtotime('-46 years')) }}</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">{{ date('Y', strtotime('-50 years')) }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>                                        
                                                </div>  
                                            </div>                               
                                        </div>                           
                                    </div>                    
                                    <div class="col">                            
                                        <div class="card">
                                            <div class="card-header" style="color: #0f4b63;"><strong>16 аас доош насныхан</strong></div>
                                            <div class="card-body" style="color: #0f4b63;">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered">
                                                        <thead>
                                                            <tr> 
                                                                <th class="text-center" style="color: #0f4b63;">Монгол нэр</th>                                                                                                                                     
                                                                <th class="text-center" style="color: #0f4b63;">Англи нэр</th>
                                                                <th class="text-center" style="color: #0f4b63;">Эхлэх нас</th>
                                                                <th class="text-center" style="color: #0f4b63;">Дуусах нас</th>
                                                                <th class="text-center" style="color: #0f4b63;">Эхлэх он</th>
                                                                <th class="text-center" style="color: #0f4b63;">Дуусах Он</th>                                                
                                                            </tr>
                                                        </thead>
                                                        <tbody>                                                    
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">Бага нас 1</td>                                                     
                                                                <td class="text-center border-right" style="color: #0f4b63;">Kids 1</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">4</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">5</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">{{ date('Y', strtotime('-4 years')) }}</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">{{ date('Y', strtotime('-5 years')) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">Бага нас 2</td>                                                     
                                                                <td class="text-center border-right" style="color: #0f4b63;">Kids 2</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">6</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">7</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">{{ date('Y', strtotime('-6 years')) }}</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">{{ date('Y', strtotime('-7 years')) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">Бага нас 3</td>                                                     
                                                                <td class="text-center border-right" style="color: #0f4b63;">Kids 3</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">8</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">9</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">{{ date('Y', strtotime('-8 years')) }}</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">{{ date('Y', strtotime('-9 years')) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">Өсвөр үе 1</td>                                                     
                                                                <td class="text-center border-right" style="color: #0f4b63;">Infant </td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">10</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">11</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">{{ date('Y', strtotime('-10 years')) }}</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">{{ date('Y', strtotime('-11 years')) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">Өсвөр үе 2</td>                                                     
                                                                <td class="text-center border-right" style="color: #0f4b63;">Junior</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">12</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">13</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">{{ date('Y', strtotime('-12 years')) }}</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">{{ date('Y', strtotime('-13 years')) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">Өсвөр үе 3</td>                                                     
                                                                <td class="text-center border-right" style="color: #0f4b63;">Teen</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">14</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">15</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">{{ date('Y', strtotime('-14 years')) }}</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">{{ date('Y', strtotime('-15 years')) }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>                                        
                                                </div>
                                            </div>
                                        </div>                           
                                    </div>                        
                                </div>
                                <h2 style="color: #0f4b63;">БҮСНИЙ АНГИЛАЛ</h2>
                                <div class="row">
                                    <div class="col-lg-4">                            
                                        <div class="card">
                                            <div class="card-header" style="color: #0f4b63;"><strong>16 аас доош насныхан</strong></div>
                                            <div class="card-body" style="color: #0f4b63;">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="5" class="text-center" style="color: #0f4b63;"><strong>Бүсний ангилалгүй</strong></th>
                                                            </tr> 
                                                            <tr>
                                                              <th class="text-center" style="color: #0f4b63;">Цагаан</th>
                                                              <th class="text-center" style="color: #0f4b63;">Саарал</th>
                                                              <th class="text-center" style="color: #0f4b63;">Шар</th>
                                                              <th class="text-center" style="color: #0f4b63;">Улбар шар</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>                                                
                                                            <tr>                                    
                                                                <td class="text-center border-right" style="color: #0f4b63;"><img src="/assets/images/belts/white.png" alt="white-belt" width="60" height="30"></td>                                                     
                                                                <td class="text-center border-right" style="color: #0f4b63;"><img src="/assets/images/belts/grey.png" alt="grey-belt" width="60" height="30"></td>
                                                                <td class="text-center border-right" style="color: #0f4b63;"><img src="/assets/images/belts/yellow.png" alt="yellow-belt" width="60" height="30"></td>
                                                                <td class="text-center border-right" style="color: #0f4b63;"><img src="/assets/images/belts/orange.png" alt="orange-belt" width="60" height="30"></td>
                                                            </tr>                                 
                                                        </tbody>
                                                    </table>                                        
                                                </div>  
                                            </div>                              
                                        </div>                           
                                    </div>
                                    <div class="col-lg-3">                            
                                        <div class="card">
                                            <div class="card-header" style="color: #0f4b63;"><strong>Залуучууд</strong></div>
                                            <div class="card-body" style="color: #0f4b63;">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="5" class="text-center" style="color: #0f4b63;"><strong>Бүсний ангилалгүй</strong></th>
                                                            </tr>  
                                                            <tr>
                                                              <th class="text-center" style="color: #0f4b63;">Цагаан</th>
                                                              <th class="text-center" style="color: #0f4b63;">Цэнхэр</th>
                                                              <th class="text-center" style="color: #0f4b63;">Ягаан</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>                                                    
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;"><img src="/assets/images/belts/white.png" alt="white-belt" width="60" height="30"></td>                                                     
                                                                <td class="text-center border-right" style="color: #0f4b63;"><img src="/assets/images/belts/blue.png" alt="blue-belt" width="60" height="30"></td>
                                                                <td class="text-center border-right" style="color: #0f4b63;"><img src="/assets/images/belts/purple.png" alt="purple-belt" width="60" height="30"></td>
                                                            </tr>                                
                                                        </tbody>
                                                    </table>                                        
                                                </div>
                                            </div>
                                        </div>                           
                                    </div>
                                    <div class="col-lg-5">                            
                                        <div class="card">
                                            <div class="card-header" style="color: #0f4b63;"><strong>Насанд хүрэгч болон Мастерс</strong></div>
                                            <div class="card-body" style="color: #0f4b63;">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="5" class="text-center" style="color: #0f4b63;"><strong>Бүсний ангилалгүй</strong></th>
                                                            </tr>
                                                            <tr>
                                                              <th class="text-center" style="color: #0f4b63;">Цагаан</th>
                                                              <th class="text-center" style="color: #0f4b63;">Цэнхэр</th>
                                                              <th class="text-center" style="color: #0f4b63;">Ягаан</th>
                                                              <th class="text-center" style="color: #0f4b63;">Бор</th>
                                                              <th class="text-center" style="color: #0f4b63;">Хар</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>                                                    
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;"><img src="/assets/images/belts/white.png" alt="white-belt" width="60" height="30"></td>                                                     
                                                                <td class="text-center border-right" style="color: #0f4b63;"><img src="/assets/images/belts/blue.png" alt="blue-belt" width="60" height="30"></td>
                                                                <td class="text-center border-right" style="color: #0f4b63;"><img src="/assets/images/belts/purple.png" alt="purple-belt" width="60" height="30"></td>
                                                                <td class="text-center border-right" style="color: #0f4b63;"><img src="/assets/images/belts/brown.png" alt="brown-belt" width="60" height="30"></td>
                                                                <td class="text-center border-right" style="color: #0f4b63;"><img src="/assets/images/belts/black.png" alt="black-belt" width="60" height="30"></td>
                                                            </tr>                                  
                                                        </tbody>
                                                    </table>                                        
                                                </div>
                                            </div>
                                        </div>                           
                                    </div>
                                </div>
                                <h2 style="color: #0f4b63;">ЖИНГИЙН АНГИЛАЛ | MJJF OFFICIAL WEIGHTS</h2>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="card">
                                            <div class="card-header" style="color: #0f4b63;"><strong>Хөвгүүд ( 16 аас доош насныхан )</strong></div>
                                            <div class="card-body" style="color: #0f4b63;">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered">
                                                        <thead>                                
                                                            <tr>
                                                                <th class="text-center" style="color: #0f4b63;">Өсвөр үе 3 <small>Teen</small></th>
                                                                <th class="text-center" style="color: #0f4b63;">Өсвөр үе 2 <small>Junior</small></th>
                                                                <th class="text-center" style="color: #0f4b63;">Өсвөр үе 1 <small>Infant</small></th>
                                                                <th class="text-center" style="color: #0f4b63;">Бага нас 3 <small>Kids 3</small></th>
                                                                <th class="text-center" style="color: #0f4b63;">Бага нас 2 <small>Kids 2</small></th>
                                                                <th class="text-center" style="color: #0f4b63;">Бага нас 1 <small>Kids 1</small></th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-center" style="color: #0f4b63;">3 минут</th>
                                                                <th class="text-center" style="color: #0f4b63;">3 минут</th>
                                                                <th class="text-center" style="color: #0f4b63;">3 минут</th>
                                                                <th class="text-center" style="color: #0f4b63;">2 минут</th>
                                                                <th class="text-center" style="color: #0f4b63;">2 минут</th>
                                                                <th class="text-center" style="color: #0f4b63;">2 минут</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-38</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-34</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-30</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-24</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-18</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-16</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-42</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-37</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-34</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-27</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-20</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-18</td>
                                                            </tr>                                        
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-46</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-41</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-37</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-30</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-23</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-21</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-50</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-45</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-41</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-34</td>                                    
                                                                <td class="text-center border-right" style="color: #0f4b63;">-26</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-24</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-56</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-50</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-45</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-38</td>
                                                                
                                                                <td class="text-center border-right" style="color: #0f4b63;">-30</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-28</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-62</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-55</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-50</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-42</td>
                                                                
                                                                <td class="text-center border-right" style="color: #0f4b63;">-34</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-32</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-67</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-60</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-55</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-50</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-38</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-36</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-72</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-66</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-60</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;"></td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-46</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-44</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-84</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-78</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-66</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;"></td>
                                                                <td class="text-center border-right" style="color: #0f4b63;"></td>
                                                                <td class="text-center border-right" style="color: #0f4b63;"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <div class="col-lg-6">
                                        <div class="card">
                                            <div class="card-header" style="color: #0f4b63;"><strong>Охид ( 16 аас доош насныхан )</strong></div>
                                            <div class="card-body" style="color: #0f4b63;">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center" style="color: #0f4b63;">Өсвөр үе 3 <small> Teen </small></th>
                                                                <th class="text-center" style="color: #0f4b63;">Өсвөр үе 2 <small> Junior </small></th>
                                                                <th class="text-center" style="color: #0f4b63;">Өсвөр үе 1 <small> Infant </small></th>
                                                                <th class="text-center" style="color: #0f4b63;">Бага нас 3 <small> Kids 3 </small></th>
                                                                <th class="text-center" style="color: #0f4b63;">Бага нас 2 <small> Kids 2 </small></th>
                                                                <th class="text-center" style="color: #0f4b63;">Бага нас 1 <small> Kids 1 </small></th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-center" style="color: #0f4b63;">3 минут</th>
                                                                <th class="text-center" style="color: #0f4b63;">3 минут</th>
                                                                <th class="text-center" style="color: #0f4b63;">3 минут</th>
                                                                <th class="text-center" style="color: #0f4b63;">2 минут</th>
                                                                <th class="text-center" style="color: #0f4b63;">2 минут</th>
                                                                <th class="text-center" style="color: #0f4b63;">2 минут</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-36</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-32</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-28</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-24</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-24</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-18</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-40</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-36</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-32</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-28</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-28</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-21</td>
                                                            </tr>                                        
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-44</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-40</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-36</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-32</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-32</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-24</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-48</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-44</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-40</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-36</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-36</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-28</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-52</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-48</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-44</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-40</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-40</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-32</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-57</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-52</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-48</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-48</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;"></td>
                                                                <td class="text-center border-right" style="color: #0f4b63;"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-63</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-57</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-52</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;"></td>
                                                                <td class="text-center border-right" style="color: #0f4b63;"></td>
                                                                <td class="text-center border-right" style="color: #0f4b63;"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-68</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-63</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-57</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;"></td>
                                                                <td class="text-center border-right" style="color: #0f4b63;"></td>
                                                                <td class="text-center border-right" style="color: #0f4b63;"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-80</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-75</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-63</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;"></td>
                                                                <td class="text-center border-right" style="color: #0f4b63;"></td>
                                                                <td class="text-center border-right" style="color: #0f4b63;"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="card">
                                            <div class="card-header" style="color: #0f4b63;"><strong>Эр ( 16 аас дээш насныхан )</strong></div>
                                            <div class="card-body" style="color: #0f4b63;">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center" style="color: #0f4b63;">Залуучууд <small> Youth</small></th>
                                                                <th class="text-center" style="color: #0f4b63;">Насанд<small style="color: #FFFFFF">.</small>хүрэгч <small> Adult</small></th>
                                                                <th class="text-center" style="color: #0f4b63;">Мастер 1 <small> Master 1</small></th>
                                                                <th class="text-center" style="color: #0f4b63;">Мастер 2 <small> Master 2</small></th>
                                                                <th class="text-center" style="color: #0f4b63;">Мастер 3 <small> Master 3</small></th>
                                                                <th class="text-center" style="color: #0f4b63;">Мастер 4 <small> Master 4</small></th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-center" style="color: #0f4b63;">4 минут</th>
                                                                <th class="text-center" style="color: #0f4b63;">5 минут</th>
                                                                <th class="text-center" style="color: #0f4b63;">5 минут</th>
                                                                <th class="text-center" style="color: #0f4b63;">5 минут</th>
                                                                <th class="text-center" style="color: #0f4b63;">5 минут</th>
                                                                <th class="text-center" style="color: #0f4b63;">5 минут</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-46</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-56</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-56</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-56</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-56</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-56</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-50</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-62</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-62</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-62</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-62</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-62</td>
                                                            </tr>                                        
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-55</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-69</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-69</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-69</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-69</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-69</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-60</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-77</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-77</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-77</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-77</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-77</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-66</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-85</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-85</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-85</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-85</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-85</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-73</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-94</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-94</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-94</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-94</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-94</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-81</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-120</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-120</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-120</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-120</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-120</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-94</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;"></td>
                                                                <td class="text-center border-right" style="color: #0f4b63;"></td>
                                                                <td class="text-center border-right" style="color: #0f4b63;"></td>
                                                                <td class="text-center border-right" style="color: #0f4b63;"></td>
                                                                <td class="text-center border-right" style="color: #0f4b63;"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                            
                                    <div class="col-lg-6">
                                        <div class="card">
                                            <div class="card-header" style="color: #0f4b63;"><strong>Эм ( 16 аас дээш насныхан )</strong></div>
                                            <div class="card-body" style="color: #0f4b63;">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center" style="color: #0f4b63;">Залуучууд <small> Youth</small></th>
                                                                <th class="text-center" style="color: #0f4b63;">Насанд хүрэгч <small> Adult</small></th>
                                                                <th class="text-center" style="color: #0f4b63;">Мастер 1 <small> Master 1</small></th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-center" style="color: #0f4b63;">4 минут</th>
                                                                <th class="text-center" style="color: #0f4b63;">5 минут</th>
                                                                <th class="text-center" style="color: #0f4b63;">5 минут</th>
                                                            </tr>                                    
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-48</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-45</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-45</td>                        
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-52</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-48</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-48</td>                                                
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-57</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-52</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-52</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-63</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-57</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-57</td>                                                
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-95</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-62</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-62</td>                                                
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;"></td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-70</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-70</td>                                                
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center border-right" style="color: #0f4b63;"></td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-95</td>
                                                                <td class="text-center border-right" style="color: #0f4b63;">-95</td>
                                                            </tr>                                      
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade in {{@$tab_id == $tab['number'] ? 'active show' : '' }}" id="tab1-2">
                                <div class="card-body">
		                            <div class="row justify-content-center text-center my-0 my-md-25">
		                            	<!-- begin: Pricing-->
		                            	<div class="col-md-4 col-xxl-3 bg-white rounded-left shadow-sm">
		                            		<div class="pt-25 pb-25 pb-md-10 px-4">
		                            			<h4 class=" mb-15">Энгийн</h4>
                                                <span class="px-7 py-3 d-inline-flex flex-center rounded-lg mb-15 bg-danger">
		                            				<span class="pr-2 opacity-70">₮</span>
		                            				<span class="pr-2 font-size-h1 font-weight-bold">20'000</span>
		                            				<span class="opacity-70">/&nbsp;&nbsp;1 жилийн</span>
		                            			</span>
                                                <p class="mb-10 d-flex flex-column text-dark-50">
		                            				<span>Lorem ipsum dolor sit amet adipiscing elit</span>
		                            				<span>sed do eiusmod tempors labore et dolore</span>
		                            				<span>magna siad enim aliqua</span>
		                            			</p>
		                            			<button type="button" class="btn btn-primary text-uppercase font-weight-bolder px-15 py-3">Purchase</button>
		                            		</div>
		                            	</div>
		                            	<!-- end: Pricing-->

		                            	<!-- begin: Pricing-->
		                            	<div class="col-md-4 col-xxl-3 bg-primary my-md-n15 rounded shadow-sm">
		                            		<div class="pt-25 pt-md-37 pb-25 pb-md-10 py-md-28 px-4">
		                            			<h4 class=" text-white mb-15">Professional</h4>
		                            			<span class="px-7 py-3 bg-white d-inline-flex flex-center rounded-lg mb-15 bg-white">
		                            				<span class="pr-2 text-primary opacity-70">₮</span>
		                            				<span class="pr-2 font-size-h1 font-weight-bold text-primary">100000</span>
		                            				<span class="text-primary opacity-70">/&nbsp;&nbsp;1 жилийн</span>
		                            			</span>
		                            			<br>
		                            			<p class="text-white mb-10 d-flex flex-column">
		                            				<span>Lorem ipsum dolor sit amet adipiscing elit</span>
		                            				<span>sed do eiusmod tempors labore et dolore</span>
		                            				<span>magna siad enim aliqua</span>
		                            			</p>
		                            			<button type="button" class="btn btn-white text-uppercase font-weight-bolder px-15 py-3">Purchase</button>
		                            		</div>
		                            	</div>
		                            	<!-- end: Pricing-->

		                            	<!-- begin: Pricing-->
		                            	<div class="col-md-4 col-xxl-3 bg-white rounded-right shadow-sm">
		                            		<div class="pt-25 pb-25 pb-md-10 px-4">
		                            			<h4 class=" mb-15">Extended</h4>
		                            			<span class="px-7 py-3 d-inline-flex flex-center rounded-lg mb-15 bg-danger">
		                            				<span class="pr-2 opacity-70">₮</span>
		                            				<span class="pr-2 font-size-h1 font-weight-bold">200000</span>
		                            				<span class="opacity-70">/&nbsp;&nbsp;1 жилийн</span>
		                            			</span>
		                            			<br>
		                            			<p class="mb-10 d-flex flex-column text-dark-50">
		                            				<span>Lorem ipsum dolor sit amet adipiscing elit</span>
		                            				<span>sed do eiusmod tempors labore et dolore</span>
		                            				<span>magna siad enim aliqua</span>
		                            			</p>
		                            			<button type="button" class="btn btn-primary text-uppercase font-weight-bolder px-15 py-3">Purchase</button>
		                            		</div>
		                            	</div>
		                            	<!-- end: Pricing-->
		                            </div>
	                            </div>
                            </div>
                            <div class="tab-pane fade in {{@$tab_id == $tab['number'] ? 'active show' : '' }}" id="tab1-3">
                                <div class="card-body bg-white col-11 col-lg-12 col-xxl-10 mx-auto">
					                <div class="row">
					                	<!-- begin: Pricing-->
					                	<div class="col-md-4">
					                		<div class="pt-30 pt-md-25 pb-15 px-5 text-center">
					                			<!--begin::Icon-->
					                			<div class="d-flex flex-center position-relative mb-25">
					                				<span class="svg svg-fill-primary opacity-4 position-absolute">
					                					<svg width="175" height="200">
					                						<polyline points="87,0 174,50 174,150 87,200 0,150 0,50 87,0"></polyline>
					                					</svg>
					                				</span>
					                				<span class="svg-icon svg-icon-5x svg-icon-primary"><!--begin::Svg Icon | path:assets/media/svg/icons/Home/Flower3.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                            <path d="M1.4152146,4.84010415 C11.1782334,10.3362599 14.7076452,16.4493804 12.0034499,23.1794656 C5.02500006,22.0396582 1.4955883,15.9265377 1.4152146,4.84010415 Z" fill="#000000" opacity="0.3"></path>
                                                            <path d="M22.5950046,4.84010415 C12.8319858,10.3362599 9.30257403,16.4493804 12.0067693,23.1794656 C18.9852192,22.0396582 22.5146309,15.9265377 22.5950046,4.84010415 Z" fill="#000000" opacity="0.3"></path>
                                                            <path d="M12.0002081,2 C6.29326368,11.6413199 6.29326368,18.7001435 12.0002081,23.1764706 C17.4738192,18.7001435 17.4738192,11.6413199 12.0002081,2 Z" fill="#000000" opacity="0.3"></path>
                                                        </g>
                                                        </svg><!--end::Svg Icon-->
                                                    </span>								
                                                </div>
								                <!--end::Icon-->
								                <!--begin::Content-->
								                <h4 class="font-size-h3 mb-10">Basic Plan</h4>
								                <div class="d-flex flex-column line-height-xl pb-10">
								                	<span>1 Domain</span>
								                	<span>10 Users</span>
								                	<span>20 Copies</span>
								                	<span>Free Assets</span>
								                </div>
								                <span class="font-size-h1 d-block font-weight-boldest text-dark">69<sup class="font-size-h3 font-weight-normal pl-1">$</sup></span>
								                <div class="mt-7">
								                	<button type="button" class="btn btn-primary text-uppercase font-weight-bolder px-15 py-3">Purchase</button>
								                </div>
								                <!--end::Content-->
							                </div>
						                </div>
						                <!-- end: Pricing-->

						                <!-- begin: Pricing-->
						                <div class="col-md-4 border-x-0 border-x-md border-y border-y-md-0">
						                	<div class="pt-30 pt-md-25 pb-15 px-5 text-center">
						                		<!--begin::Icon-->
						                		<div class="d-flex flex-center position-relative mb-25">
						                			<span class="svg svg-fill-primary opacity-4 position-absolute">
						                				<svg width="175" height="200">
						                					<polyline points="87,0 174,50 174,150 87,200 0,150 0,50 87,0"></polyline>
						                				</svg>
						                			</span>
						                			<span class="svg-icon svg-icon-5x svg-icon-primary"><!--begin::Svg Icon | path:assets/media/svg/icons/Tools/Compass.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"></rect>
                                                            <path d="M7.07744993,12.3040451 C7.72444571,13.0716094 8.54044565,13.6920474 9.46808594,14.1079953 L5,23 L4.5,18 L7.07744993,12.3040451 Z M14.5865511,14.2597864 C15.5319561,13.9019016 16.375416,13.3366121 17.0614026,12.6194459 L19.5,18 L19,23 L14.5865511,14.2597864 Z M12,3.55271368e-14 C12.8284271,3.53749572e-14 13.5,0.671572875 13.5,1.5 L13.5,4 L10.5,4 L10.5,1.5 C10.5,0.671572875 11.1715729,3.56793164e-14 12,3.55271368e-14 Z" fill="#000000" opacity="0.3"></path>
                                                            <path d="M12,10 C13.1045695,10 14,9.1045695 14,8 C14,6.8954305 13.1045695,6 12,6 C10.8954305,6 10,6.8954305 10,8 C10,9.1045695 10.8954305,10 12,10 Z M12,13 C9.23857625,13 7,10.7614237 7,8 C7,5.23857625 9.23857625,3 12,3 C14.7614237,3 17,5.23857625 17,8 C17,10.7614237 14.7614237,13 12,13 Z" fill="#000000" fill-rule="nonzero"></path>
                                                        </g>
                                                        </svg><!--end::Svg Icon-->
                                                    </span>
                                                </div>
								                <!--end::Icon-->
						                		<!--begin::Content-->
						                		<h4 class="font-size-h3 mb-10">For Business</h4>
						                		<div class="d-flex flex-column line-height-xl mb-10">
						                			<span>10 Domains</span>
						                			<span>Unlimited Users</span>
						                			<span>Unlimited Copies</span>
						                			<span>Free Assets</span>
						                		</div>
						                		<span class="font-size-h1 d-block font-weight-boldest text-dark">169<sup class="font-size-h3 font-weight-normal pl-1">$</sup></span>
						                		<div class="mt-7">
						                			<button type="button" class="btn btn-primary text-uppercase font-weight-bolder px-15 py-3">Purchase</button>
						                		</div>
						                		<!--end::Content-->
						                	</div>
						                </div>
						                <!-- end: Pricing-->
						                <!-- begin: Pricing-->
						                <div class="col-md-4">
						                	<div class="pt-30 pt-md-25 pb-15 px-5 text-center">
						                		<!--begin::Icon-->
						                		<div class="d-flex flex-center position-relative mb-25">
						                			<span class="svg svg-fill-primary opacity-4 position-absolute">
						                				<svg width="175" height="200">
						                					<polyline points="87,0 174,50 174,150 87,200 0,150 0,50 87,0"></polyline>
						                				</svg>
						                			</span>
						                			<span class="svg-icon svg-icon-5x svg-icon-primary"><!--begin::Svg Icon | path:assets/media/svg/icons/Shopping/Cart2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"></rect>
                                                            <path d="M12,4.56204994 L7.76822128,9.6401844 C7.4146572,10.0644613 6.7840925,10.1217854 6.3598156,9.76822128 C5.9355387,9.4146572 5.87821464,8.7840925 6.23177872,8.3598156 L11.2317787,2.3598156 C11.6315738,1.88006147 12.3684262,1.88006147 12.7682213,2.3598156 L17.7682213,8.3598156 C18.1217854,8.7840925 18.0644613,9.4146572 17.6401844,9.76822128 C17.2159075,10.1217854 16.5853428,10.0644613 16.2317787,9.6401844 L12,4.56204994 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                                            <path d="M3.28077641,9 L20.7192236,9 C21.2715083,9 21.7192236,9.44771525 21.7192236,10 C21.7192236,10.0817618 21.7091962,10.163215 21.6893661,10.2425356 L19.5680983,18.7276069 C19.234223,20.0631079 18.0342737,21 16.6576708,21 L7.34232922,21 C5.96572629,21 4.76577697,20.0631079 4.43190172,18.7276069 L2.31063391,10.2425356 C2.17668518,9.70674072 2.50244587,9.16380623 3.03824078,9.0298575 C3.11756139,9.01002735 3.1990146,9 3.28077641,9 Z M12,12 C11.4477153,12 11,12.4477153 11,13 L11,17 C11,17.5522847 11.4477153,18 12,18 C12.5522847,18 13,17.5522847 13,17 L13,13 C13,12.4477153 12.5522847,12 12,12 Z M6.96472382,12.1362967 C6.43125772,12.2792385 6.11467523,12.8275755 6.25761704,13.3610416 L7.29289322,17.2247449 C7.43583503,17.758211 7.98417199,18.0747935 8.51763809,17.9318517 C9.05110419,17.7889098 9.36768668,17.2405729 9.22474487,16.7071068 L8.18946869,12.8434035 C8.04652688,12.3099374 7.49818992,11.9933549 6.96472382,12.1362967 Z M17.0352762,12.1362967 C16.5018101,11.9933549 15.9534731,12.3099374 15.8105313,12.8434035 L14.7752551,16.7071068 C14.6323133,17.2405729 14.9488958,17.7889098 15.4823619,17.9318517 C16.015828,18.0747935 16.564165,17.758211 16.7071068,17.2247449 L17.742383,13.3610416 C17.8853248,12.8275755 17.5687423,12.2792385 17.0352762,12.1362967 Z" fill="#000000"></path>
                                                        </g>
                                                        </svg><!--end::Svg Icon-->
                                                    </span>
                                                </div>
								                <!--end::Icon-->
				                				<!--begin::Content-->
				                				<h4 class="font-size-h3 mb-10">Enterprise</h4>
				                				<div class="d-flex flex-column line-height-xl mb-10">
				                					<span>Unlimited Domain</span>
				                					<span>Unlimited Users</span>
				                					<span>Unlimited Copies</span>
				                					<span>Free Assets</span>
				                				</div>
				                				<span class="font-size-h1 d-block font-weight-boldest text-dark">669<sup class="font-size-h3 font-weight-normal pl-1">$</sup></span>
				                				<div class="mt-7">
				                					<button type="button" class="btn btn-primary text-uppercase font-weight-bolder px-15 py-3">Purchase</button>
				                				</div>
				                				<!--end::Content-->
				                			</div>
				                		</div>
				                		<!-- end: Pricing-->
				                	</div>
				                </div>
                            </div>
                            <div class="tab-pane fade in {{@$tab_id == $tab['number'] ? 'active show' : '' }}" id="tab1-4">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="card" style="width: 18rem;">
                                            <img src="/assets/images/rule/mjjf1.png" class="card-img-top" alt="uniq_rule">
                                            <div class="card-body">
                                                <h5 class="card-title">MJJF Rule</h5>
                                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                                <a href="#" class="btn btn-primary">Go somewhere</a>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-3">
                                        <div class="card" style="width: 18rem;">
                                            <img src="/assets/images/rule/ajp.png" class="card-img-top" alt="ajp_rule">
                                            <div class="card-body">
                                                <h5 class="card-title">AJPTour Rule</h5>
                                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                                <a href="#" class="btn btn-primary">Go somewhere</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="card" style="width: 18rem;">
                                            <img src="/assets/images/rule/jjif.png" class="card-img-top" alt="jjif_rule">
                                            <div class="card-body">
                                                <h5 class="card-title">JJIF Rule</h5>
                                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                                <a href="#" class="btn btn-primary">Go somewhere</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="card" style="width: 18rem;">
                                            <img src="/assets/images/rule/adcc.png" class="card-img-top" alt="adcc_rule">
                                            <div class="card-body">
                                                <h5 class="card-title">ADCC Rule</h5>
                                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLong">
                                                    Launch demo modal
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            
                        </div>
                    </div>
                </div>
                <!--end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
    <!--end::Content-->
