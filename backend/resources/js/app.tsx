import '../css/app.css';
import { createRoot } from 'react-dom/client';
import DiagnosisApp from './components/DiagnosisApp';

const container = document.getElementById('app');
if (container) {
    const root = createRoot(container);
    root.render(<DiagnosisApp />);
}
