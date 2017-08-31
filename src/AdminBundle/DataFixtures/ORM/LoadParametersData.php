<?php

namespace AdminBundle\DataFixtures\ORM;

use AdminBundle\Entity\Parameters;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadParametersData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $parameters = [
            ['label' => 'Propriétaire', 'value' => 'IMIE'],
            ['label' => 'Adresse', 'value' => '213 Route de Rennes, 44700 Orvault'],
            ['label' => 'Copyrights', 'value' => 'Copyright © IMIE I Développement et Webdesign'],
            ['label' => 'Mentions légales', 'value' => '<h1>Mentions l&eacute;gales</h1>

<div class="region region-content">
<div class="block block-system" id="block-system-main">
<div class="content">
<div class="clearfix node node-page node-promoted" id="node-42">
<div class="content">
<div class="field field-label-hidden field-name-body field-type-text-with-summary">
<div class="field-items">
<div class="even field-item">
<h2>OBJET</h2>

<p>Les pr&eacute;sentes ont pour objet de fixer les conditions d&rsquo;utilisation du site web accessible &agrave; l&rsquo;adresse http://www.imie.fr/</p>
Conform&eacute;ment aux dispositions de l&rsquo;article 6 III 1&deg; de la loi n&deg;2004-575 du 21 juin 2004 pour la confiance dans l&rsquo;&eacute;conomie num&eacute;rique, nous vous informons que :

<p>Ce site est &eacute;dit&eacute; par la soci&eacute;t&eacute; IMIE, SARL au capital de 85000 &euro; immatricul&eacute;e au RCS Nantes (num&eacute;ro 508 422 979) dont le si&egrave;ge social se situe 1 Rue Victor Hugo Immeuble Agora 44400 REZE - T&eacute;l&eacute;phone : 02 28 01 37 54</p>

<p>L&rsquo;h&eacute;bergement est assur&eacute; par la soci&eacute;t&eacute; OVH, soci&eacute;t&eacute; par actions simplifi&eacute;e au capital de 10 000 000 Euros dont le si&egrave;ge social se situe 2 rue Kellermann 59100 ROUBAIX et immatricul&eacute;e au Registre du Commerce et des Soci&eacute;t&eacute;s de Roubaix- Tourcoing sous le num&eacute;ro 424 761 419 00045, code APE 6202A.</p>

<p>Le directeur de la publication est Vincent Plan&ccedil;on.</p>

<div class="separateur">&nbsp;</div>

<h2>DROITS D&#39;AUTEUR SUR LES DOCUMENTS FIGURANT SUR LE SITE DE L&#39;IMIE</h2>

<p>La cr&eacute;ation et la conception du site est assur&eacute;e par ACTANCE.</p>

<p>Les documents publi&eacute;s sur le site sont couverts par le droit d&#39;auteur. Toute reprise est conditionn&eacute;e au respect du droit de la propri&eacute;t&eacute; intellectuelle au regard des auteurs et ayant-droits des photographies, infographies, &oelig;uvres d&#39;art... &eacute;ventuellement inclus dans les documents ou sur le site de l&#39;IMIE.</p>

<p>Les droits pour ces documents n&rsquo;ont &eacute;t&eacute; acquitt&eacute;s que pour l&rsquo;utilisation qui en est faite. Toute utilisation ult&eacute;rieure pourrait entrer en conflit avec les droits de propri&eacute;t&eacute; intellectuelle pr&eacute;existants.</p>

<div class="separateur">&nbsp;</div>

<h2>CR&Eacute;ATION DE LIENS VERS www.imie.fr</h2>

<p>L&#39;IMIE autorise tout site Internet ou tout autre support &agrave; citer le site ou &agrave; mettre en place un lien hypertexte pointant vers son contenu. L&rsquo;autorisation de mise en place d&rsquo;un lien est valable pour tout site, &agrave; l&rsquo;exception de ceux diffusant des informations &agrave; caract&egrave;re pol&eacute;mique, pornographique, x&eacute;nophobe ou pouvant, dans une plus large mesure, porter atteinte &agrave; la sensibilit&eacute; du plus grand nombre, ou causer un pr&eacute;judice quelconque &agrave; l&#39;IMIE, &agrave; son image ainsi qu&rsquo;&agrave; celle de l&rsquo;ensemble de ses salari&eacute;s, ses &eacute;tudiants ou de ses partenaires.</p>

<p>Pour ce faire, dans le respect des droits de leur auteur, un logo est disponible, sur simple demande adress&eacute;e par courrier &eacute;lectronique au service de la communication (&agrave; l&rsquo;adresse : contact@imie.fr) pour illustrer le lien en pr&eacute;cisant que le site d&rsquo;origine est celui de l&#39;IMIE.</p>

<div class="separateur">&nbsp;</div>

<h2>INFORMATIQUE ET LIBERT&Eacute;S</h2>

<p>Plusieurs services du site n&eacute;cessite, pour l&rsquo;internaute, de renseigner des donn&eacute;es personnelles par le biais de formulaires en ligne.</p>

<p>Les informations demand&eacute;es, signal&eacute;es par un ast&eacute;risque sont n&eacute;cessaires au traitement des demandes. Les autres informations sont destin&eacute;es &agrave; mieux conna&icirc;tre nos internautes et sont, par cons&eacute;quent, facultatives.</p>

<p>Ces informations sont destin&eacute;es &agrave; l&#39;IMIE, responsable du traitement, aux fins de gestion des comptes clients, d&rsquo;&eacute;tudes marketing et statistiques dans le but de fournir les services les plus adapt&eacute;es et de suivi de qualit&eacute; de nos services.</p>

<p>Dans le cas o&ugrave; l&rsquo;internaute ne s&rsquo;y sera pas oppos&eacute; explicitement lors du recueil de ses coordonn&eacute;es, l&#39;IMIE pourra lui adresser ses offres commerciales par mail, t&eacute;l&eacute;phone ou par voie postale.</p>

<p>A tout moment, l&rsquo;internaute garde la possibilit&eacute; de s&rsquo;opposer sans frais &agrave; la prospection commerciale de l&#39;IMIE, en contactant le webmestre du site &agrave; l&rsquo;adresse contact@imie.fr</p>

<p>Les informations recueillies pourront &eacute;ventuellement &ecirc;tre communiqu&eacute;es &agrave; des tiers li&eacute;s &agrave; l&#39;IMIE par contrat en raison de sous-traitance partielle des prestataires. Ces prestataires s&rsquo;engagent contractuellement &agrave; garantir un niveau de s&eacute;curit&eacute;, de confidentialit&eacute; et de protection suffisant de la vie priv&eacute;e et des droits fondamentaux.</p>

<p>Conform&eacute;ment &agrave; la loi n&deg; 2004-801 du 6 ao&ucirc;t 2004 relative &agrave; la protection des personnes physiques &agrave; l&rsquo;&eacute;gard des traitements de donn&eacute;es &agrave; caract&egrave;re personnel modifiant la loi n&deg;78-17 du 6 janvier 1978, les internautes disposent d&rsquo;un droit d&rsquo;acc&egrave;s, de rectification et de suppression des donn&eacute;es les concernant et d&rsquo;opposition &agrave; leur traitement en contactant le Correspondant informatique et libert&eacute; de l&#39;IMIE &agrave; l&rsquo;adresse contact@imie.fr.</p>

<div class="separateur">&nbsp;</div>

<h2>COOKIES</h2>

<p>En vue d&rsquo;adapter le site aux demandes de ses visiteurs des mesures du nombre de visites, de pages vues ainsi que de l&#39;activit&eacute; des visiteurs sur le site et de leur fr&eacute;quence de retour sont effectu&eacute;es.</p>

<p>L&#39;outil statistique de mesure de la fr&eacute;quentation du site Google analytics, que nous utilisons, est, de mani&egrave;re g&eacute;n&eacute;rale, bas&eacute; sur la seule analyse des &laquo; logs &raquo; de connexions sur les serveurs. Toutefois, certaines pages peuvent contenir un marqueur t&eacute;moin (&laquo; cookie &raquo;).</p>

<p>Conform&eacute;ment &agrave; l&#39;article 8.1 des conditions d&#39;utilisation du service Google analytics, nous attirons l&#39;attention de nos visiteurs sur les conditions d&#39;utilisation de ce service, consultables ici.</p>

<p>L&rsquo;internaute peut refuser ces &laquo; t&eacute;moins &raquo; ou les supprimer sans que cela entra&icirc;ne une difficult&eacute; d&rsquo;acc&egrave;s aux pages du site. Pour s&rsquo;opposer &agrave; l&#39;enregistrement de &laquo; t&eacute;moins &raquo; ou &ecirc;tre pr&eacute;venu avant de l&#39;accepter, nous vous conseillons de consulter la rubrique d&#39;aide de votre navigateur qui pr&eacute;cise la marche &agrave; suivre.</p>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>'],
            ['label' => 'Taille d\'affichage des images (en pixel)', 'value' => '{ "width": 230, "height": 125, "emptyImageString": "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOUAAAB8CAYAAACWoHedAAADCUlEQVR4Xu3TQREAAAgCQelf2hr3WBMwi+wcAQIpgaXSCEOAwBmlJyAQEzDKWCHiEDBKP0AgJmCUsULEIWCUfoBATMAoY4WIQ8Ao/QCBmIBRxgoRh4BR+gECMQGjjBUiDgGj9AMEYgJGGStEHAJG6QcIxASMMlaIOASM0g8QiAkYZawQcQgYpR8gEBMwylgh4hAwSj9AICZglLFCxCFglH6AQEzAKGOFiEPAKP0AgZiAUcYKEYeAUfoBAjEBo4wVIg4Bo/QDBGICRhkrRBwCRukHCMQEjDJWiDgEjNIPEIgJGGWsEHEIGKUfIBATMMpYIeIQMEo/QCAmYJSxQsQhYJR+gEBMwChjhYhDwCj9AIGYgFHGChGHgFH6AQIxAaOMFSIOAaP0AwRiAkYZK0QcAkbpBwjEBIwyVog4BIzSDxCICRhlrBBxCBilHyAQEzDKWCHiEDBKP0AgJmCUsULEIWCUfoBATMAoY4WIQ8Ao/QCBmIBRxgoRh4BR+gECMQGjjBUiDgGj9AMEYgJGGStEHAJG6QcIxASMMlaIOASM0g8QiAkYZawQcQgYpR8gEBMwylgh4hAwSj9AICZglLFCxCFglH6AQEzAKGOFiEPAKP0AgZiAUcYKEYeAUfoBAjEBo4wVIg4Bo/QDBGICRhkrRBwCRukHCMQEjDJWiDgEjNIPEIgJGGWsEHEIGKUfIBATMMpYIeIQMEo/QCAmYJSxQsQhYJR+gEBMwChjhYhDwCj9AIGYgFHGChGHgFH6AQIxAaOMFSIOAaP0AwRiAkYZK0QcAkbpBwjEBIwyVog4BIzSDxCICRhlrBBxCBilHyAQEzDKWCHiEDBKP0AgJmCUsULEIWCUfoBATMAoY4WIQ8Ao/QCBmIBRxgoRh4BR+gECMQGjjBUiDgGj9AMEYgJGGStEHAJG6QcIxASMMlaIOASM0g8QiAkYZawQcQgYpR8gEBMwylgh4hAwSj9AICZglLFCxCFglH6AQEzAKGOFiEPAKP0AgZiAUcYKEYeAUfoBAjEBo4wVIg6BBzNcAH1h5ASLAAAAAElFTkSuQmCC" }'],
            ['label' => 'Texte d\'acceuil', 'value' => '<p>Bienvenue ! Pour commencer le test, cliquez sur le bouton ci-dessous.</p>'],
            ['label' => 'Titre du site', 'value' => 'Titre du site'],
            ['label' => 'Couleur primaire', 'value' => '#ff0000'],
            ['label' => 'Couleur secondaire', 'value' => '#ffff00'],
            ['label' => 'Couleur texte', 'value' => '#ffffff'],
        ];

        foreach($parameters as $parameter)
        {
            $param = new Parameters();
            $param->setLabel($parameter['label'])
                ->setValue($parameter['value']);

            $manager->persist($param);
            $manager->flush();
        }
    }
}