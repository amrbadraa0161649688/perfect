@extends('Layouts.Tracking.claim-layout')
@section('content')
    <!-- Tracking-->
    <div class="container">
        <div class="stepper">

            <h1 class="section-title tracking-title">{{__('claim')}}</h1>
           
            <section class="contact-wrapper">
    <div class="container">
        <div class="contact-us">
            <div class="row">
                <div class="col-12 col-lg-7">
                    <form action="" class="contact-form">
                        <!--<div class="section-title-block">
                            <div class="section-title">
                                Contact Us
                                <span class="shape-1"></span>
                                <span class="shape-2"></span>
                            </div>
                        </div>-->

                        <h3>أرسل لنا رسالة</h3>
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                <label>الاسم </label>
                                    <input type="text" class="form-control" name="" required>
                                   
                                </div>
                            </div>
                            
                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                <label> البريد الالكترونى</label>
                                    <input type="email" class="form-control" name="" required>
                                  
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                <label>الهاتف</label>
                                    <input type="tel" class="form-control" name="" required>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                        <label>الرسالة</label>
                            <textarea class="form-control" name="" id="" rows="5" required></textarea>
                          
                        </div>
                        <div class="text-center">
                            <button  type="submit" class="btn btn-primary">
                                ارسال
                                <i class="far fa-send"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-12 col-lg-5">
                    <div class="contact-menu-wrapper">
                        <h3>ابقى على تواصل</h3>
                        <ul class="contact-menu">
                            <li>
                                <div class="contact-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="contact-details">
                                    <h6 style="color: green , font-weight: bold ">اتصل بنا</h6>
                                    <a style="color: green" href="tel:+966505555470">	+966505555470</a>
                                </div>
                            </li>
                            <li>
                                <div class="contact-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="contact-details">
                                    <h6>Email Us</h6>
                                    <a style="color: green" href="mailto:	info@Waqoodi.com">	info@Waqoodi.com</a>
                                </div>
                            </li>
                            <li>
                                <div class="contact-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="contact-details">
                                    <h6>Our Address</h6>
                                    <a  style="color: green" href="https://goo.gl/maps/HUJo8vkXks1Tmt9WA" target="_blank">طريق الجنادريه - حي الرمال - الرياض - المملكة العربية السعودية</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
        </div>
    </div>
@endsection