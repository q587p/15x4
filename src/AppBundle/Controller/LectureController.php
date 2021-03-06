<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration as Extra;
use AppBundle\Entity;
use Symfony\Component\HttpFoundation\Request;

class LectureController extends AbstractController
{
    /**
     * @Extra\Route("/lecture/discussion/{id}/", name="LectureDetailsLong")
     */
    public function detailsOldAction(Request $request)
    {
        return $this->redirectToRoute('LectureDetails', ['id' => $request->get('id')]);
    }

    /**
     * @Extra\Route("/L/{id}/", name="LectureDetails")
     * @Extra\Route("/l/{id}/", name="LectureDetailsAlt")
     * @Extra\ParamConverter()
     */
    public function detailsAction(Entity\Lecture $lecture)
    {
        return $this->render('lecture/details.html.twig', [
            "lecture" => $lecture,
        ]);
    }

    /**
     * @Extra\Route("/lecture/random/", name="LectureRandom")
     */
    public function randomAction(Request $request)
    {
        return $this->render('lecture/details.html.twig', [
            'lecture' => $this->getLectureRepository()->getRandom($request->get('lang')),
        ]);
    }

    /**
     * @Extra\Route("/lectures/", name="LectureList")
     */
    public function listAllAction(Request $request)
    {
        $tagIds = explode(',', $request->get('tags', ''));
        $eventIds = explode(',', $request->get('events', ''));
        $fieldIds = explode(',', $request->get('fields', ''));
        $lecturerIds = explode(',', $request->get('lecturers', ''));
        $languages = array_filter(explode(',', $request->get('langs', '')));

        $tags = $this->getTagRepository()->findByIds($tagIds);
        $events = $this->getEventRepository()->findByIds($eventIds);
        $fields = $this->getFieldRepository()->findByIds($fieldIds);
        $speakers = $this->getLecturerRepository()->findByIds($lecturerIds);

        return $this->render(
            'lecture/list.html.twig',
            [
                'pagination' => $this->getPager()->paginate(
                    $this->getLectureRepository()->findByFilters($fields, $tags, $events, $speakers, $languages),
                    $request->get('page', 1),
                    40
                ),
                'isFiltered' => $tags || $events || $fields || $speakers,
                'filters' => [
                    'tags' => [
                        'selected' => $tags,
                        'available' => $this->getTagRepository()->findForFilter($tagIds),
                    ],
                    'events' => [
                        'selected' => $events,
                        'available' => $this->getEventRepository()->findForFilter($eventIds),
                    ],
                    'fields' => [
                        'selected' => $fields,
                        'available' => $this->getFieldRepository()->findForFilter($fieldIds),
                    ],
                    'speakers' => [
                        'selected' => $speakers,
                        'available' => $this->getLecturerRepository()->findForFilter($lecturerIds),
                    ],
                    'langs' => [
                        'selected' => $languages
                    ]
                ]
            ]
        );
    }

    /**
     * @Extra\Route("/lectures/featured/", name="LectureListFeatured")
     */
    public function listFeaturedAction(Request $request)
    {
        return $this->render(
            'lecture/featured.html.twig',
            [
                'pagination' => $this->getPager()->paginate(
                    $this
                        ->getLectureRepository()
                        ->createListQueryBuilder()
                        ->andWhere('lecture.isFeatured = 1')
                        ->orderBy('event.date', 'desc'),
                    $request->get('page', 1),
                    30
                ),
            ]
        );
    }

    /**
     * @Extra\Route("/lecture/city/{id}", name="LectureByCity")
     * @Extra\ParamConverter()
     */
    public function listByCityAction(Entity\City $city)
    {
        return $this->redirectToRoute(
            'LectureList',
            ['events' => implode(',', $city->getEventIds())]
        );
    }

    /**
     * @Extra\Route("/lecture/field/{id}", name="LectureByField")
     * @Extra\ParamConverter()
     */
    public function listByFieldAction(Entity\Field $field, Request $request)
    {
        return $this->render('lecture/by-field.html.twig', [
            'pagination' => $this->getPager()->paginate(
                $this->getLectureRepository()->findByFilters([ $field ]),
                $request->get('page', 1),
                10
            ),
            'tags' => $this->getFieldRepository()->findWithTags($field)[0]['tags'],
            'field' => $field,
        ]);
    }

    /**
     * @Extra\Route("/lectures/favorite", name="MyFavoriteLectures")
     */
    public function myFavoritesAction(Request $request)
    {
        if (null === $this->getUser()) {
            return $this->redirectToRoute('LectureList');
        }

        return $this->render(
            'lecture/favorites.html.twig',
            [
                'pagination' => $this->getPager()->paginate(
                    $this->getLectureRepository()->findByIds($this->getUser()->getFavoriteLectureIds()),
                    $request->get('page', 1),
                    10
                ),
            ]
        );
    }
}
