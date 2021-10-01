<?php

namespace TwinElements\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TwinElements\AdminBundle\Service\AdminTranslator;

class SortableController extends AbstractController
{
    /**
     * @Route("te_sortable/sort", name="te_sortable_sort", methods={"POST"}, options={"expose"=true, "i18n"=false})
     */
    public function sortableAction(Request $request, AdminTranslator $translator)
    {
        try {
            if (!$request->isXmlHttpRequest()) {
                throw $this->createAccessDeniedException();
            }

            if (!$request->request->has('sortableData') || !$request->request->has('entity')) {
                throw new \Exception('No sortable data or entity');
            }

            $sortableData = explode(',', $request->request->get('sortableData'));
            $entity = $request->request->get('entity');

            $repository = $this->getDoctrine()->getRepository($entity);

            if (is_null($repository)) {
                throw new \Exception($translator->translate('te_sortable.no_access'));
            }

            $em = $this->getDoctrine()->getManager();

            $count = 0;
            foreach ($sortableData as $id) {
                $update = $repository->find($id);

                if (is_null($update)) {
                    throw new \Exception($translator->translate('te_sortable.no_items'));
                }

                $update->setPosition($count);
                $em->persist($update);
                $count++;
            }
            $em->flush();


            return new Response(json_encode(['done' => $translator->translate('te_sortable.changed_done')]));
        } catch (\Exception $exception) {
            return new Response(json_encode(['error' => $exception->getMessage()]));
        }
    }
}
