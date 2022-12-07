<form action="/contact" method="post">
    @csrf
    <div id="contact_id"></div>
    <div class="field">
        <label for="name" class="label is-size-4 has-text-weight-light"></label>
        <div class="control has-icons-left">
            <input type="text" name="name" id="name" class="input" placeholder="Name" autofocus>
            <span class="icon is-left">
                <i class="fa fa-user"></i>
            </span>
        </div>
    </div>
    <div class="field">
        <label for="email" class="label is-size-4 has-text-weight-light"></label>
        <div class="control has-icons-left">
            <input type="email" name="email" id="email" class="input" placeholder="Email">
            <span class="icon is-left">
                <i class="fa fa-envelope"></i>
            </span>
        </div>
    </div>
    <div class="field">
        <label for="message" class="label is-size-4 has-text-weight-light"></label>
        <textarea name="message" id="message" rows="5" class="textarea is-medium" placeholder="Message"></textarea>
    </div>
    <button type="submit" class="button is-success is-size-5">Submit</button>
</form>
