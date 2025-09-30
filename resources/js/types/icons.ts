// Типы для иконок Ant Design
export interface IconProps {
    spin?: boolean
    rotate?: number
    style?: Record<string, any>
    className?: string
    onClick?: (event: MouseEvent) => void
}

// Основные иконки, используемые в приложении
export type IconName =
    | 'HomeOutlined'
    | 'FileTextOutlined'
    | 'MessageOutlined'
    | 'UserOutlined'
    | 'SettingOutlined'
    | 'CheckCircleOutlined'
    | 'PlayCircleOutlined'
    | 'InfoCircleOutlined'
    | 'ArrowRightOutlined'
    | 'CopyrightOutlined'
    | 'GithubOutlined'
    | 'QuestionCircleOutlined'
    | 'SmileOutlined'
    | 'SearchOutlined'
    | 'PlusOutlined'
    | 'EditOutlined'
    | 'DeleteOutlined'
    | 'SaveOutlined'
    | 'DownloadOutlined'
    | 'UploadOutlined'
    | 'LinkOutlined'
    | 'CopyOutlined'
    | 'CloseCircleOutlined'
    | 'ExclamationCircleOutlined'
    | 'LoadingOutlined'
    | 'LeftOutlined'
    | 'RightOutlined'
    | 'UpOutlined'
    | 'DownOutlined'
    | 'MenuOutlined'
    | 'BarsOutlined'
    | 'TwitterOutlined'
    | 'FacebookOutlined'
    | 'LinkedinOutlined'
    | 'InstagramOutlined'
    | 'SyncOutlined'
    | 'ReloadOutlined'
