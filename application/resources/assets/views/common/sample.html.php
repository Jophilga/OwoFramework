{% extends {% returns \framework\libraries\owo\classes\Cores\OwoCoreView::getTemplatePath|minimal.tmpl.php %} %}


{% block meta %}
<style>
    html,body {
        max-width: 100%;
        max-height: 100%;
        width: 100%;
        height: 100%;
        margin: 0;
        text-align: center;
        font-family: system-ui;
        font-size: 12px;
        background: #102030;
        color: #ffffff;
    }
    section#header {
        height: 70px;
        background: #ffcc00;
        color: #000000;
        margin-bottom: 1em;
    }
    section#main {
        padding: 10px;
    }
    section#footer {
        padding: 10px;
        background: #ffcc00;
        color: #000000;
    }
    p.catch {
        padding: 10px;
    }
    textarea {
        width: 80%;
            padding: 10px;
        text-align: center;
        background: #ffffff;
        color: #333333;
    }
</style>
{% endblock %}


{% block content %}
<section id="header"></section>

<section id="main">
    <h2>{{{ $title }}}</h2>
    <p>{{{ $message }}}</p>
</section>

<section id="footer">
    <p>Copyright © {{ \date('Y') }} All Rights Reserved.</p>
</section>
{% endblock %}
