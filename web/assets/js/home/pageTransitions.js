function PageTransionner()
{
    // Variable
    this.main = $('#pt-main');
    this.pages = this.main.children("div.pt-page");
    this.pagesCount = this.pages.length;
    this.current = 0;
    this.isAnimating = false;
    this.endCurrPage = false;
    this.endNextPage = false;
    this.animEndEventNames = {
        'WebkitAnimation' : 'webkitAnimationEnd',
        'OAnimation' : 'oAnimationEnd',
        'msAnimation' : 'MSAnimationEnd',
        'animation' : 'animationend'
    };
    this.animEndEventName = this.animEndEventNames[ Modernizr.prefixed('animation')];
    this.support = Modernizr.cssanimations;

    // Fonction d'initialisation du process
    this.init = function ()
    {
        var that = this;
        // Set le passage a la page suivante
        this.pages.each( function()
        {
            var $page = $( this );
            $page.data( 'originalClassList', $page.attr( 'class' ) );
        } );

        // Set la première page
        // this.pages.eq( this.current ).addClass( 'pt-page-current' );

        this.pages.eq( this.current ).removeClass( 'hidden');

        // Envois du nextPage
        $('#nextButton').unbind('click').bind('click', function()
        {
            that.nextPage();
        });
    };
    // Fonction qui permet de passer à la page d'après
    this.nextPage = function (callback)
    {
        var that = this;

        // Empeche le lancement de plusieur animation en même temps
        if( this.isAnimating )
        {
            return false;
        }

        // Empeche le lancement de plusieur animation en même temps
        this.isAnimating = true;

        var $currPage = this.pages.eq( this.current );

        // Permet d'éviter de dépasser le nombre de page max
        if( this.current < this.pagesCount - 1 )
        {
            ++this.current;
        }
        else
        {
            this.current = 0;
        }

        // On récupère la page suivante
        // var $nextPage = this.pages.eq( this.current ).addClass( 'pt-page-current');

        var $nextPage = this.pages.eq( this.current ).removeClass( 'hidden');

        // On ajoute la classe qui enlève la page en cours
        $currPage.addClass('pt-page-rotateSlideOut').on( this.animEndEventName, function()
        {
            $currPage.off( that.animEndEventName );
            that.endCurrPage = true;
            if( that.endNextPage )
            {
                that.onEndAnimation( $currPage, $nextPage);
            }
        });

        // On ajoute la classe qui ramène la page suivante
        $nextPage.addClass('pt-page-rotateSlideIn').on( this.animEndEventName, function()
        {
            $nextPage.off( that.animEndEventName );
            that.endNextPage = true;
            if( that.endCurrPage )
            {
                that.onEndAnimation( $currPage, $nextPage);
            }
        });

        if( !this.support )
        {
            this.onEndAnimation( $currPage, $nextPage);
        }


        callback();
    };

    // Fonction appeler a la fin de nextPage
    this.onEndAnimation = function ($outpage, $inpage)
    {
        this.endCurrPage = false;
        this.endNextPage = false;
        this.resetPage($outpage, $inpage);
        this.isAnimating = false;
    };

    // Reset la page
    this.resetPage = function ($outpage, $inpage)
    {
        // $outpage.attr('class', $outpage.data('originalClassList'+ ' hidden'));
        // $inpage.attr('class', $inpage.data('originalClassList'));

        $outpage.attr('class', "pt-page hidden");
        $outpage.attr('data-active', false);

        $inpage.attr('class', "pt-page");
        $inpage.attr('data-active', true);
    };

    this.init();
}
