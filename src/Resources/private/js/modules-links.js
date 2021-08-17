import { ChooseLinkModal } from "./ChooseLinkModal";

const linkChooseModals = Array.from(document.querySelectorAll('.choose-link-modal'));
linkChooseModals.map((modal) => new ChooseLinkModal(modal.id));


