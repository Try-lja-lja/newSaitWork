<template id="programModal">
<?php
if (!isset($lang)) {
    $lang = 'ka';
}
?>
<h2><?php echo $lang === 'ka' ? 'პროგრამის შესახებ' : 'About the Programme'; ?></h2>

<div class="program-modal-text">
  <?php if ($lang === 'ka'): ?>
    <p>ქართულის, როგორც უცხო ენის, სწავლება დამოუკიდებელ მიმართულებად XXI საუკუნეში ჩამოყალიბდა. ეს პროცესი კი დაიწყო 2010 წელს საქართველოს განათლებისა და მეცნიერების სამინისტროს ქართულის, როგორც უცხო ენის, სწავლების პროგრამა „ირბახით.“ პროგრამის მიზანმიმართულად განვითარების ერთ-ერთი შედეგია ვებგვერდი www.geofl.ge, რომელიც მუდმივად ივსება „ირბახის“ ფარგლებში შექმნილი სასწავლო-მეთოდური და დამხმარე მასალებით. პროგრამის შექმნას დიდად შეუწყო ხელი ევროპის საბჭოს ენობრივი პოლიტიკის სამმართველოსთან თანამშრომლობამ, რამაც განაპირობა პროგრამის თანამედროვე იერსახე − მასში გათვალისწინებულია საერთოევროპული ენობრივი კომპეტენციები და შექმნილია ახალი პლატფორმა საქართველოს ენობრივი განათლების სივრცეში.</p>
    
    <p>პროგრამის მიზანია შემსწავლელებისა და მასწავლებლებისათვის ქართულის, როგორც უცხო ენის, სწავლა-სწავლებისთვის აუცილებელი რესურსების სრული პაკეტის შეთავაზება, რისთვისაც თავდაპირველად შეიქმნა ქართული ენის ფლობის დონეების სტანდარტული აღწერილობა. ქართულის, როგორც უცხო ენის, სტანდარტიზაციის აუცილებლობა მას შემდეგ გაჩნდა, რაც ქართული სახელმწიფო ინტენსიურად ჩაერთო საერთაშორისო პროცესებში და, შესაბამისად, შეიქმნა ქართული ენის განხილვის საჭიროება ორი საურთიერთო კონტექსტით: ქართული, როგორც საქართველოს სახელმწიფო ენა, ანუ შიდა მიმოქცევის ენა და ქართული, როგორც მსოფლიო მიმოქცევაში მყოფი ერთ-ერთი ენა, ანუ ლეგიტიმირებული ენა. ქართული ენის ფლობის დონეების სტანდარტული აღწერილობა შემუშავდა იმ პარამეტრების შესაბამისად, რომლებიც მიღებულია თანამედროვე ევროპული ენების ლინგვოდიდაქტიკურ აღწერილობათა სფეროში, რამაც განაპირობა ქართული ენის სწავლების ინტეგრირება ენობრივი განათლების საერთოევროპულ სისტემაში. ეს კი ნიშნავს, რომ სახელმწიფო ენის სტატუსის გარდა, ქართულმა ენამ საერთაშორისო მიმოქცევის ენის სტატუსიც შეიძინა. აღნიშნული სტანდარტის პრეზენტაცია შედგა ევროპის საბჭოში.</p>
    
    <p>ქართულის, როგორც უცხო ენის, სტანდარტული აღწერილობის საფუძველზე შედგა სასწავლო-მეთოდური სახელმძღვანელოები და დამხმარე მასალები. სახელმძღვანელოები შექმნილია სწავლების კომუნიკაციური მიდგომის გათვალისწინებით და ორიენტირებულია შემსწავლელებზე ისე, რომ მათ შეძლონ რეალურ, ცხოვრებისეულ სიტუაციებში გარკვევა და კომუნიკაციის დამყარება. აღნიშნული სახელმძღვანელოები ხელს შეუწყობს ყოველდღიური და საქმიანი ურთიერთობებისთვის საჭირო უნარ-ჩვევების განვითარებას.</p>
    
    <p>ვებგვერდით სარგებლობენ უცხოელები როგორც საქართველოში, ასევე საზღვარგარეთ, ქართული დიასპორების მომავალი თაობები და ყველა ადამიანი, ვინც ინტერესდება ქართული ენისა და კულტურის შესწავლით.</p>

    <p>ვებგვერდზე www.geofl.ge განთავსებული მასალა განკუთვნილია ბავშვების, მოზარდებისა და ზრდასრულთათვის.  იგი გათვალისწინებულია შემსწავლელისა და მასწავლებლის კოორდინირებული მუშაობისთვის.</p>
  <?php else: ?>
    <p>Teaching Georgian, as a foreign language became formed into an independent field in the 21st century. The process itself began with the Programme Irbach – Teaching Georgian as a Foreign Language initiated by the Ministry of Education and Science of Georgia. One of the results of the persistent development of the Programme is a web-site www.geofl.ge , which is regularly supplemented with the teaching, methodical and auxiliary materials generated within the Programme Irbach. The development of the Programme was substantially assisted by the collaboration with the Language Policy Division of the Council of Europe, which defined the current distinctive characteristics of the Programme – it takes into consideration the Common European language competences and provides a new educational platform within the field of the language education of Georgia.</p>
    
    <p>The aim of the Programme is to provide the learners and teachers with the full package of necessary resources for the learning and teaching of Georgian as a foreign language, for which purpose the standard of reference levels of Georgian was developed. The need to standardize the Georgian language arose when the Georgian state became actively involved in international processes and, accordingly, it became necessary to regard the Georgian language in two communicative contexts: Georgian as the State language, that is the language for internal communication, and Georgian as a language used in the worldwide communication, that is, a legitimized language. The standard of Reference Levels of Georgian was developed in accordance with the parameters as adopted in the field of lingvodidactic descriptions of European languages, which fact led to the integration of Georgian into the Common European system of language education. This also means that, in addition to the State-language status, Georgian acquired the status of a language of international communication. The presentation of the said standard of reference levels took place at the Council of Europe.</p>
    
    <p>Based on the Standard of Reference Levels of Georgian as a Foreign Language, there were developed educational-methodical textbooks and auxiliary materials. The textbooks are compiled in accordance with the communicative approach to teaching and are oriented to the learners, so that they could adequately understand the real, everyday-life situations and establish communication. The said textbooks will contribute to the acquisition of the skills which are necessary for daily and business-related communications.</p>
    
    <p>The web-site is used by foreigners both in Georgia and abroad, by different generations of the Georgian Diaspora, as well as by each and every person interested in the study of the Georgian language and culture.</p>

    <p>The information displayed on the web-site www.geofl.ge is generated for children, teenagers and adults and is intended for the coordinated work of language learner and teacher.</p>
  <?php endif; ?>
</div>
</template>
