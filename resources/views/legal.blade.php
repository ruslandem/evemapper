@extends('layouts.app')
@section('title', '- Legal')

@section('content')
    <div class="container main-content">
        <h1 class="title is-1 has-text-centered mt-3 mb-5">Legal Notice & Information</h1>
        <div class="p-6 is-darker scrolling">

            <div class="content">
                <h3 class="title">About EveMapper</h3>
                <p>EveMapper is an online tool to help you with some aspects playing Eve Online game.</p>
            </div>

            <div class="content">
                <h3 class="title">Support EveMapper</h3>
                <p>EveMapper is a personal project and developed during my free time.
                    If you like it you can support the project and make a donation.</p>

                <p>
                    <span class="has-text-warning mr-3"><b>In Game:</b></span>
                    Send ISK to <span class="tag has-text-weight-bold">Khazad Tyori</span>
                </p>

                <p>
                    <span class="has-text-warning mr-3"><b>PayPal:</b></span>

                    <span>
                        <form action="https://www.paypal.com/donate" method="post" target="_top">
                            <input type="hidden" name="business" value="DAZXD3ZWAYLLA" />
                            <input type="hidden" name="no_recurring" value="0" />
                            <input type="hidden" name="item_name" value="EveMapper Project" />
                            <input type="hidden" name="currency_code" value="USD" />
                            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif"
                                border="0" name="submit" title="PayPal - The safer, easier way to pay online!"
                                alt="Donate with PayPal button" />
                            <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif"
                                width="1" height="1" />
                        </form>
                    </span>


                </p>
            </div>

            <div class="content">
                <h3 class="title">Contact me</h3>
                <p>You are welcome to contact me in-game by sending evemail to <span class="tag has-text-weight-bold">Khazad
                        Tyori</span>
                    or by sending your message on EveMapper contact page <a href="/contact">here</a>.</p>
            </div>

            <div class="content">
                <h3 class="title">Privacy Policy</h3>
                <p>You can see our privacy policy <a href="/privacy">here</a>.</p>
            </div>

            <div class="content">
                <h3 class="title">CCP Copyright Notice</h3>
                <p>
                    EVE Online and the EVE logo are the registered trademarks of CCP hf. All rights are reserved worldwide.
                    All other trademarks are the property of their respective owners. EVE Online, the EVE logo, EVE and all
                    associated logos and designs are the intellectual property of CCP hf. All artwork, screenshots,
                    characters, vehicles, storylines, world facts or other recognizable features of the intellectual
                    property relating to these trademarks are likewise the intellectual property of CCP hf. CCP hf. has
                    granted permission to EveMapper to use EVE Online and all associated logos and designs for
                    promotional and information purposes on its website but does not endorse, and is not in any way
                    affiliated with, EveMapper. CCP is in no way responsible for the content on or functioning of this
                    website, nor can it be liable for any damage arising from the use of this website.
                </p>
                <p>
                    This application has been created under the <a
                        href="https://developers.eveonline.com/resource/license-agreement">EVE Developer License
                        Agreement</a>.
                </p>
            </div>

        </div>
    </div>

    <style>
        .scrolling {
            height: 80vh;
            overflow-y: auto;
        }
    </style>
@endsection
